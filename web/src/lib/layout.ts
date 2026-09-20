import type { GraphNode, GraphSnapshot, LayoutDescriptor, Sector } from "./types";

export interface Placed {
  id: string;
  x: number;
  y: number;
  r: number;
  angle: number;
  radius: number;
  sector: Sector | null;
}

export interface SectorArc {
  id: Sector;
  start: number; // radians
  end: number;
  label: { en: string; ar: string };
}

export interface LayoutResult {
  placed: Record<string, Placed>;
  sectors: SectorArc[];
  unit: number;
  pills: { id: string; sector: Sector; label: { en: string; ar: string }; radius: number; start: number; end: number; thickness: number; labelSide: "inside" | "outside" }[];
  bands: { id: string; sector: Sector; label: { en: string; ar: string }; radius: number; start: number; end: number; thickness: number; labelSide: "inside" | "outside" }[];
}

const TAU = Math.PI * 2;
const deg = (d: number) => (d * Math.PI) / 180;

// CivLab scale: elected 17 (President override), chambers 14, departments 12..18 by children, commissions 12, advisory 11, heads 8
const radiusByType: Record<string, number> = { constituency: 16, elected: 17, department: 14, commission: 14, advisory: 13, dept_head: 7, seat: 4 };
const BADGE_R = 6; // heads are drawn as a small badge attached to their body's glyph (CivLab)
function nodeRadius(n: GraphNode): number {
  if (n.type === "department") {
    const kids = Math.min(n.children?.length ?? 0, 8);
    return 14 + (kids / 8) * 6;
  }
  if (isTopOffice(n)) return 15;
  return radiusByType[n.type] ?? 10;
}
// Selection only rotates the wheel and resizes the glyph: no sector widening, no neighbour
// compression, no stagger change, so nothing reshuffles when a node is chosen.
const STAGGER = 15;              // CivLab rowOffset: alternate radial offset in dense groups
/** Highest-authority offices: drawn on the elected ring, always visible (CivLab's President/VP circles). */
export const TOP_OFFICE_KINDS = new Set(["president", "prime_minister", "speaker"]);
export const isTopOffice = (n: GraphNode) => n.type === "dept_head" && TOP_OFFICE_KINDS.has(n.kind ?? "");



function matches(node: GraphNode, match: Record<string, unknown>): boolean {
  return Object.entries(match).every(([k, v]) => (node as unknown as Record<string, unknown>)[k] === v);
}

/**
 * Deterministic radial "lb-sectors" layout (decisions.md Q9 + research amendments).
 * Centre: electorate. Four angular sectors sized by minAngleDeg. Inside a sector,
 * top-level bodies are spread evenly by angle on a ring chosen by bucket/band/pill;
 * children sit deeper at their parent's angle; head positions sit just outside their body.
 */
export interface LayoutOptions {
  /** radians added to every angle (CivLab: π/2 − angle(selected) so the focus sits at 6 o'clock) */
  rotation?: number;
  /** the selected node (kept for future use; positions do not depend on it) */
  focusId?: string | null;
}

export function computeLayout(snapshot: GraphSnapshot, width: number, height: number, opts: LayoutOptions = {}): LayoutResult {
  const L: LayoutDescriptor = snapshot.layout;
  const rotation = opts.rotation ?? 0;
  void opts.focusId;
  const nodes = snapshot.nodes;
  const unit = Math.min(width, height) / 9.0;
  const placed: Record<string, Placed> = {};
  const alias = (s: Sector | null): Sector | null => (s && L.sectorAliases?.[s]) || s;

  // --- sector arcs, clockwise from the top, gaps between
  const gap = deg(L.sectorGapDeg ?? 4);
  const weights = L.sectors.map((s) => s.minAngleDeg);
  const totalMin = weights.reduce((a, w) => a + w, 0);
  const available = TAU - gap * L.sectors.length;
  let cursor = -Math.PI / 2 + gap / 2 + rotation;
  const sectors: SectorArc[] = L.sectors.map((s, i) => {
    const span = (weights[i] / totalMin) * available;
    const arc = { id: s.id, start: cursor, end: cursor + span, label: s.label };
    cursor += span + gap;
    return arc;
  });
  const arcOf = (s: Sector | null) => sectors.find((a) => a.id === s) ?? sectors[0];

  // --- centre
  const center = nodes[L.centerNodeId];
  if (center) placed[center.id] = { id: center.id, x: 0, y: 0, r: radiusByType.constituency, angle: 0, radius: 0, sector: null };

  // --- ring / band / pill assignment for bodies
  const ringSpacing: Record<string, number> = {};
  for (const r of L.rings) ringSpacing[r.bucket] = r.spacing;

  type Slot = { node: GraphNode; radius: number; group: string };
  const bySector: Record<string, Slot[]> = {};
  const pillsOut: LayoutResult["pills"] = [];
  const bandsOut: LayoutResult["bands"] = [];

  const bodies = Object.values(nodes).filter((n) => n.id !== L.centerNodeId && n.type !== "dept_head" && n.type !== "seat");
  // A body with an explicit band or pill hint is positioned on that band even when it has a parent
  // (security services sit under ministries but belong on the security band).
  const hinted = (n: GraphNode) => { const h = (n.layoutHints ?? {}) as Record<string, unknown>; return h.band !== undefined || h.pill !== undefined || h.ring !== undefined; };
  // children of a body on a pill or on the outer ring cannot go "one step beyond" it: they spread on
  // their own ring instead (the fan line still ties them to the parent)
  const parentOnPill = (n: GraphNode) => {
    const par = n.parent ? nodes[n.parent] : null;
    if (!par) return false;
    const h = (par.layoutHints ?? {}) as Record<string, unknown>;
    if (h.pill !== undefined) return true;
    const parIsTop = !par.parent || !nodes[par.parent] || nodes[par.parent].type === "dept_head";
    const parOnOuterRing = parIsTop && h.band === undefined && h.ring === undefined && !(L.apexNodeIds ?? []).includes(par.id) && par.type !== "elected";
    return parOnOuterRing;
  };
  const topLevel = bodies.filter((n) => hinted(n) || parentOnPill(n) || !n.parent || !nodes[n.parent] || nodes[n.parent].type === "dept_head");

  const topOffices = Object.values(nodes).filter((n) => isTopOffice(n) && n.headOf && nodes[n.headOf]);
  for (const n of [...topLevel, ...topOffices]) {
    const sector = alias(n.sector ?? (n.headOf ? nodes[n.headOf]?.sector ?? null : null));
    if (!sector) continue;
    if (isTopOffice(n)) {
      (bySector[sector] ??= []).push({ node: n, radius: (ringSpacing.elected ?? 1.4) * unit, group: "ring:elected" });
      continue;
    }
    const hints = (n.layoutHints ?? {}) as Record<string, unknown>;
    // an explicit ring hint puts a body on that ring even when it has a parent (Parliament's committees)
    const ringKey = typeof hints.ring === "string" && ringSpacing[hints.ring] ? hints.ring : n.type;
    let radius = (ringSpacing[ringKey] ?? 2.7) * unit;
    let group = `ring:${ringKey}`;
    const pill = L.pills.find((p) => hints.pill === p.id || p.bodyNodeId === n.id);
    if (pill && hints.pill === pill.id) {
      radius = (pill.spacing ?? 1.85) * unit;
      group = `pill:${pill.id}`;
    } else if (L.apexNodeIds?.includes(n.id)) {
      radius = 1.25 * unit;
      group = "apex";
    } else {
      const band = L.bands.find((b) => b.sector === sector && (hints.band === b.id || matches(n, b.match)));
      if (band) {
        radius = band.radius * unit;
        group = `band:${band.id}`;
      }
    }
    (bySector[sector] ??= []).push({ node: n, radius, group });
  }

  // --- spread top-level bodies by angle within each sector, grouped by ring so labels interleave
  for (const arc of sectors) {
    const slots = (bySector[arc.id] ?? []).sort((a, b) => a.group.localeCompare(b.group) || a.radius - b.radius || (a.node.name.en ?? "").localeCompare(b.node.name.en ?? ""));
    const pad = deg(4);
    const span = arc.end - arc.start - pad * 2;
    // Each group (ring, pill, band, apex) spreads across the whole sector on its own radius, as in
    // CivLab's per-ring `to()`; radii keep the rings apart, and a ring too dense for its radius staggers.
    // groups that share a radius in this sector are spread together, otherwise they would land on each other
    const groups = new Map<string, Slot[]>();
    for (const sl of slots) {
      const key = sl.group.startsWith("pill:") || sl.group.startsWith("band:") ? sl.group : `r:${Math.round(sl.radius)}`;
      (groups.get(key) ?? groups.set(key, []).get(key)!).push(sl);
    }
    for (const list of groups.values()) {
      const gid = list[0].group;
      const n = list.length;
      const radius = list[0].radius;
      const inset = gid.startsWith("pill:") ? deg(3) : n === 1 ? span / 2 : Math.min(deg(6), span * 0.08);
      const a0 = arc.start + pad + inset, a1 = arc.end - pad - inset;
      // spread evenly across the sector, but never wider than a comfortable pitch: small groups stay
      // compact around the sector's middle instead of stretching to its edges
      const maxPitch = (2 * Math.max(...list.map((sl) => nodeRadius(sl.node))) + 44) / radius;
      const pitchRad = n === 1 ? 0 : Math.min((a1 - a0) / (n - 1), maxPitch);
      const mid = (a0 + a1) / 2;
      const angles = list.map((_, i) => mid + (i - (n - 1) / 2) * pitchRad);
      const pitch = n > 1 ? ((a1 - a0) / (n - 1)) * radius : Infinity;
      const glyph = Math.max(...list.map((sl) => nodeRadius(sl.node)));
      const dense = pitch < 2 * glyph + 8;
      list.forEach((sl, k) => {
        const angle = angles[k];
        const r = radius + (dense ? (k % 2 === 0 ? -STAGGER : STAGGER) : 0);
        placed[sl.node.id] = { id: sl.node.id, x: Math.cos(angle) * r, y: Math.sin(angle) * r, r: nodeRadius(sl.node), angle, radius: r, sector: arc.id };
      });
      const first = angles[0], last = angles[n - 1];
      // capsule outline around the group: room for both stagger rows, the glyphs and their badges,
      // and a clear margin at each end (all derived from the glyph size, so it scales with the nodes)
      const margin = glyph + 14;
      const padRad = margin / radius;
      const thickness = (dense ? 2 * STAGGER : 0) + 2 * glyph + 2 * 14;
      if (gid.startsWith("pill:")) {
        const p = L.pills.find((x) => `pill:${x.id}` === gid)!;
        pillsOut.push({ id: p.id, sector: arc.id, label: p.label, radius, start: first - padRad, end: last + padRad, thickness: Math.max(p.thickness, thickness), labelSide: p.labelSide ?? "inside" });
      } else if (gid.startsWith("band:")) {
        const b = L.bands.find((x) => `band:${x.id}` === gid)!;
        bandsOut.push({ id: b.id, sector: arc.id, label: b.label, radius, start: first - padRad, end: last + padRad, thickness, labelSide: b.labelSide ?? "inside" });
      }
    }
  }

  // --- children: deeper than the parent, fanned around the parent's angle
  const childrenOf = new Map<string, GraphNode[]>();
  for (const n of bodies) if (n.parent && !hinted(n) && !parentOnPill(n) && placed[n.id] === undefined && nodes[n.parent]) (childrenOf.get(n.parent) ?? childrenOf.set(n.parent, []).get(n.parent)!).push(n);
  const placeChildren = (parentId: string, depth: number) => {
    const kids = (childrenOf.get(parentId) ?? []).sort((a, b) => a.name.en!.localeCompare(b.name.en!));
    const p = placed[parentId];
    if (!p || kids.length === 0) return;
    const fan = deg(Math.min(10 * kids.length, 26));
    kids.forEach((k, idx) => {
      const angle = p.angle + (kids.length === 1 ? 0 : -fan / 2 + (fan * idx) / (kids.length - 1));
      const radius = p.radius + 0.62 * unit * depth;
      placed[k.id] = { id: k.id, x: Math.cos(angle) * radius, y: Math.sin(angle) * radius, r: nodeRadius(k) * 0.85, angle, radius, sector: p.sector };
      placeChildren(k.id, 1);
    });
  };
  for (const n of topLevel) placeChildren(n.id, 1);
  // any body still unplaced (parent chain broken) goes on the outer rim of its sector
  for (const n of bodies) if (!placed[n.id]) {
    const arc = arcOf(alias(n.sector));
    const angle = (arc.start + arc.end) / 2;
    const radius = 3.5 * unit;
    placed[n.id] = { id: n.id, x: Math.cos(angle) * radius, y: Math.sin(angle) * radius, r: nodeRadius(n), angle, radius, sector: arc.id };
  }

  // --- heads are badges attached to their body's glyph (top-left corner, stacked along the top edge),
  // in the same frame as the body so they turn with it. Highest-authority offices have their own slots.
  const heads = Object.values(nodes).filter((n) => (n.type === "dept_head" && !isTopOffice(n)) || n.type === "seat");
  const headsByBody = new Map<string, GraphNode[]>();
  for (const h of heads) if (h.headOf) (headsByBody.get(h.headOf) ?? headsByBody.set(h.headOf, []).get(h.headOf)!).push(h);
  for (const [bodyId, list] of headsByBody) {
    const p = placed[bodyId];
    if (!p) continue;
    list.sort((a, b) => (a.name.en ?? "").localeCompare(b.name.en ?? ""));
    list.forEach((h, idx) => {
      const x = p.x - p.r * 0.55 + idx * (BADGE_R * 2 + 2);
      const y = p.y - p.r * 0.55;
      placed[h.id] = { id: h.id, x, y, r: BADGE_R, angle: p.angle, radius: p.radius, sector: p.sector };
    });
  }

  return { placed, sectors, unit, pills: pillsOut, bands: bandsOut };
}

