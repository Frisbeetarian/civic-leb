"use client";
import { useEffect, useMemo, useRef, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import type { GraphSnapshot, GraphNode, GraphEdge } from "@/lib/types";
import { localized } from "@/lib/types";
import { computeLayout, isTopOffice, type Placed } from "@/lib/layout";
import { edgeStyle, edgeFamily, familyColor } from "@/lib/palette";
import { Glyph, glyphKind } from "./Glyph";

const sectorVar = (s: string | null | undefined) => (s ? `var(--c-${s})` : "var(--c-constituency)");
const TAU = Math.PI * 2;
const TWEEN = "750ms cubic-bezier(.4,0,.2,1)";

export function GraphView({ snapshot, selected, hidden, showAllEdges, onSelect, onHoverNode, onPreview, onPreviewEdge, mobile = false }: { snapshot: GraphSnapshot; selected: string | null; hidden: Set<string>; showAllEdges: boolean; onSelect: (id: string | null) => void; onHoverNode?: (id: string | null) => void; onPreview?: (id: string | null) => void; onPreviewEdge?: (id: string | null) => void; mobile?: boolean }) {
  const locale = useLocale();
  const isRtl = locale === "ar";
  const t = useTranslations();
  const ref = useRef<HTMLDivElement>(null);
  const [size, setSize] = useState({ width: 0, height: 0 });
  const [hover, setHover] = useState<string | null>(null);
  const [previewId, setPreviewId] = useState<string | null>(null);
  // on touch, the first tap previews a node (its edges light up and a chip names it); the second opens it
  const tapNode = (id: string) => {
    if (!mobile) { onSelect(id); return; }
    if (previewId === id || selected === id) { setPreviewId(null); onPreview?.(null); onSelect(id); return; }
    setPreviewEdgeId(null); onPreviewEdge?.(null);
    setPreviewId(id); setHover(id); onPreview?.(id); onHoverNode?.(id);
  };
  const [previewEdgeId, setPreviewEdgeId] = useState<string | null>(null);
  const clearPreview = () => { setPreviewId(null); setPreviewEdgeId(null); setHover(null); onPreview?.(null); onPreviewEdge?.(null); };
  // on touch, tapping an edge highlights it and shows its relationship in the chip
  const tapEdge = (id: string) => { setPreviewId(null); onPreview?.(null); setPreviewEdgeId(id); onPreviewEdge?.(id); };
  const [tip, setTip] = useState<{ kind: "node" | "edge" | "fan"; id: string; x: number; y: number } | null>(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const ro = new ResizeObserver(() => setSize({ width: el.clientWidth, height: el.clientHeight }));
    ro.observe(el);
    setSize({ width: el.clientWidth, height: el.clientHeight });
    return () => ro.disconnect();
  }, []);

  // CivLab focus: rotate the wheel so the selected node sits at 6 o'clock (π/2 in SVG space), keeping
  // rotation continuous across selections so the tween always takes the shortest way round.
  const [rotation, setRotation] = useState(0);
  const [rotatedFor, setRotatedFor] = useState<string | null>(null);
  const rotKey = `${selected ?? ""}|${mobile ? "m" : "d"}`;
  if (rotKey !== rotatedFor && size.width && size.height) {
    // derive the next rotation from the previous one so the tween takes the shortest way round
    setRotatedFor(rotKey);
    if (selected) {
      // rotation is a pure angular offset, so measure the target on the focused (widened, compressed) geometry at rotation 0
      const p = computeLayout(snapshot, size.width, size.height, { mobile }).placed[selected];
      if (p && p.radius > 0) {
        const target = Math.PI / 2 - p.angle;
        let delta = ((target - rotation) % TAU + TAU) % TAU;
        if (delta > Math.PI) delta -= TAU;
        setRotation(rotation + delta);
      }
    }
  }
  const layout = useMemo(() => (size.width && size.height ? computeLayout(snapshot, size.width, size.height, { rotation, focusId: selected, mobile }) : null), [snapshot, size, selected, rotation, mobile]);
  // Territories, pills and rulers are drawn unrotated and spun as a group with a CSS rotate transition:
  // a true rotation, so the wedges keep their shape while they turn.
  const base = useMemo(() => (size.width && size.height ? computeLayout(snapshot, size.width, size.height, { mobile }) : null), [snapshot, size, mobile]);
  // once the tween has run, edges may attach; until then they would point at stale positions
  const [settled, setSettled] = useState<string | null>(null);
  const drag = useRef<{ startAngle: number; startRot: number; lastAngle: number; lastT: number; vel: number; moved: boolean; id: number } | null>(null);
  const [dragging, setDragging] = useState(false);
  const glideRef = useRef<number | null>(null);
  const suppressClick = useRef(false);
  useEffect(() => {
    const id = window.setTimeout(() => setSettled(selected ?? "none"), 760);
    return () => window.clearTimeout(id);
  }, [selected, layout]);
  const visible = (n: GraphNode) => !hidden.has(glyphKind(n)) && !(n.type === "dept_head" && hidden.has("dept_head"));
  // heads are hidden at rest and revealed for the focused family (or when the user shows all edges)
  const headShown = (n: GraphNode) => n.type !== "dept_head" || (!hidden.has("dept_head") && (!mobile || isTopOffice(n)));

  const focusId = selected ?? hover;
  const neighbours = useMemo(() => {
    if (!focusId) return null;
    const n = snapshot.nodes[focusId];
    return new Set([focusId, ...(n?.connectedNodes ?? [])]);
  }, [focusId, snapshot]);

  if (!layout || !base) return <div ref={ref} className="absolute inset-0" />;
  const { placed, sectors, pills, bands, unit } = layout;
  // mobile: the wheel's centre sits low in the band so its upper part fills the view; the selected node,
  // rotated to 6 o'clock, lands just above the band's bottom edge (CivLab's clipHorizontal framing)
  // mobile: the centre sits near the top of the band so the wheel's lower half (executive at the bottom)
  // fills it; the top sectors are cropped by the header, as in CivLab's phone view
  const cx = size.width / 2, cy = mobile ? Math.max(1.15 * unit, size.height - 3.95 * unit - 8) : size.height / 2;
  // "is headed by" is shown by the badge attached to the body, so its edge is not drawn on the canvas
  const edges = Object.values(snapshot.edges).filter((e) => e.type !== "dept_head" && placed[e.fromId] && placed[e.toId] && visible(snapshot.nodes[e.fromId]) && visible(snapshot.nodes[e.toId]));
  const hoveredEdge = mobile ? previewEdgeId : tip?.kind === "edge" ? tip.id : null;
  const hoveredEdgeObj = hoveredEdge ? snapshot.edges[hoveredEdge] : null;
  const nodeAlpha = (id: string) => {
    if (hoveredEdgeObj) return hoveredEdgeObj.fromId === id || hoveredEdgeObj.toId === id ? 1 : selected ? "var(--connected)" : 0.55;
    return !neighbours ? 1 : neighbours.has(id) ? 1 : selected ? "var(--connected)" : 0.7;
  };
  const edgeAlpha = (e: GraphEdge): string | number => {
    if (hoveredEdge === e.id) return 1;
    const touches = focusId !== null && (e.fromId === focusId || e.toId === focusId);
    if (selected) return touches ? 1 : 0.04;
    if (hover) return touches ? 0.8 : showAllEdges ? "var(--edge-idle)" : 0;
    // with nothing selected, edges are off unless the legend's "show all relationships" is on
    return showAllEdges ? "var(--edge-all)" : 0;
  };
  const center = snapshot.nodes[snapshot.layout.centerNodeId];
  const innerR = 1.05 * unit;   // inner disc edge (CivLab: electedRadius - 30)
  const outerR = 3.95 * unit;   // territory outer edge
  const side = Math.ceil(2 * (outerR + 40)); // rotating layer: a square that contains the whole wheel
  const point = (ev: React.MouseEvent) => { const r = ref.current!.getBoundingClientRect(); return { x: ev.clientX - r.left, y: ev.clientY - r.top }; };

  // mobile: one-finger drag on the band rotates the wheel around its centre (taps still select);
  // a short momentum glide follows the release. No CSS transition while dragging.
  const angleAt = (clientX: number, clientY: number) => {
    const r = ref.current!.getBoundingClientRect();
    return Math.atan2(clientY - r.top - cy, clientX - r.left - cx);
  };
  const onPointerDown = (ev: React.PointerEvent) => {
    if (!mobile || ev.pointerType === "mouse") return;
    if (glideRef.current) { cancelAnimationFrame(glideRef.current); glideRef.current = null; }
    const a = angleAt(ev.clientX, ev.clientY);
    drag.current = { startAngle: a, startRot: rotation, lastAngle: a, lastT: performance.now(), vel: 0, moved: false, id: ev.pointerId };
  };
  const onPointerMove = (ev: React.PointerEvent) => {
    const d = drag.current;
    if (!d || ev.pointerId !== d.id) return;
    const a = angleAt(ev.clientX, ev.clientY);
    let delta = a - d.startAngle;
    while (delta > Math.PI) delta -= TAU;
    while (delta < -Math.PI) delta += TAU;
    if (!d.moved && Math.abs(delta) * Math.max(80, Math.hypot(ev.clientX - (ref.current!.getBoundingClientRect().left + cx), ev.clientY - (ref.current!.getBoundingClientRect().top + cy))) < 8) return;
    if (!d.moved) { d.moved = true; setDragging(true); (ev.currentTarget as HTMLElement).setPointerCapture?.(ev.pointerId); }
    const now = performance.now();
    let step = a - d.lastAngle; while (step > Math.PI) step -= TAU; while (step < -Math.PI) step += TAU;
    d.vel = 0.7 * d.vel + 0.3 * (step / Math.max(1, now - d.lastT));
    d.lastAngle = a; d.lastT = now;
    setRotation(d.startRot + delta);
  };
  const onPointerUp = () => {
    const d = drag.current;
    drag.current = null;
    if (!d || !d.moved) return;
    setDragging(false);
    suppressClick.current = true;
    setTimeout(() => { suppressClick.current = false; }, 300);
    // momentum: decay the angular velocity (rad/ms) until it is negligible
    let v = d.vel * 16, rot = rotation;
    const glide = () => {
      v *= 0.92; rot += v; setRotation(rot);
      if (Math.abs(v) > 0.0008) glideRef.current = requestAnimationFrame(glide); else glideRef.current = null;
    };
    if (Math.abs(v) > 0.002) glideRef.current = requestAnimationFrame(glide);
  };
  const turning = (selected !== null && settled !== selected) || dragging;
  return (
    <div ref={ref} className="absolute inset-0 overflow-hidden" style={mobile ? { touchAction: "pan-y" } : undefined}
      onPointerDown={onPointerDown} onPointerMove={onPointerMove} onPointerUp={onPointerUp} onPointerCancel={onPointerUp}
      onClickCapture={(ev) => { if (suppressClick.current) { ev.stopPropagation(); ev.preventDefault(); suppressClick.current = false; } }}>
      {/* rotating layer: an HTML element with one compositor-driven transform, so the turn never repaints.
          Glyphs tilt with the wheel, as CivLab's do. */}
      {/* the rotating SVG is a square covering the wheel's full diameter, centred on the wheel, so its
          own edges never clip the territories; the band's overflow does the cropping with fixed edges */}
      <div className="absolute" style={{ left: cx - side / 2, top: cy - side / 2, width: side, height: side, transform: `rotate(${rotation}rad)`, transformOrigin: "50% 50%", transition: dragging ? "none" : `transform ${TWEEN}`, willChange: "transform" }}>
        <svg className="graph-svg" width={side} height={side} viewBox={`${-side / 2} ${-side / 2} ${side} ${side}`} style={{ overflow: "visible" }} onClick={() => { if (mobile && (previewId || previewEdgeId)) clearPreview(); else onSelect(null); }}>
          <g>
            {base.sectors.map((s) => (
              <path key={s.id} d={wedge(innerR, outerR, s.start, s.end)} fill={sectorVar(s.id)} style={{ opacity: "var(--territory)" }} />
            ))}
            {[...base.pills, ...base.bands].map((g) => mobile
              ? <path key={g.id} d={arcPath(g.radius, g.start, g.end)} fill="none" stroke="var(--seam)" strokeDasharray="2 4" />
              : <path key={g.id} d={arcBand(g.radius, g.start, g.end, g.thickness)} fill="var(--node-fill)" fillOpacity={0.35} stroke={sectorVar(g.sector)} strokeOpacity={0.55} />
            )}
            {Object.values(snapshot.nodes).filter((n) => n.id !== center?.id && visible(n)).sort((a, b) => Number(a.type === "dept_head") - Number(b.type === "dept_head")).map((n) => {
              const p = base.placed[n.id];
              if (!p) return null;
              const color = sectorVar(n.sector);
              const isSel = selected === n.id;
              const dashed = n.status === "never_constituted" || n.status === "dormant" || n.status === "expired_continuing" || n.status === "dissolved";
              // growth waits for the turn to finish so the rotation stays a pure compositor animation
              const grow = isSel && !turning ? (n.type === "seat" ? 2 : mobile ? 1.7 : n.type === "commission" || n.type === "advisory" ? 1.5 : 1.3) : 1;
              const shown = headShown(n);
              return (
                <g key={n.id} data-id={n.id} style={{ transform: `translate(${p.x}px, ${p.y}px) scale(${grow})`, opacity: shown ? nodeAlpha(n.id) : 0, pointerEvents: shown ? "auto" : "none", cursor: "pointer", transition: "transform 250ms, opacity 250ms" }}
                  onClick={(ev) => { ev.stopPropagation(); tapNode(n.id); }}
                  onMouseEnter={() => { if (mobile) return; const q = placed[n.id]; setHover(n.id); setTip({ kind: "node", id: n.id, x: cx + q.x, y: cy + q.y - q.r * grow - 2 }); onHoverNode?.(n.id); }}
                  onMouseLeave={() => { if (mobile) return; setHover(null); setTip(null); }}>
                  {mobile && <circle r={n.type === "seat" ? Math.max(p.r + 1, 4) : 14} fill="transparent" />}
                  <Glyph kind={glyphKind(n)} r={p.r} color={color} dashed={dashed} selected={isSel} />
                </g>
              );
            })}
          </g>
        </svg>
      </div>

      {/* static top layer: labels, edges, fan lines and the centre seal, on the rotated geometry */}
      <svg className="graph-svg absolute inset-0 w-full h-full" style={{ pointerEvents: "none" }}>
        <defs>
          {["filled", "hollow", "chevron", "double"].map((k) => (
            <marker key={k} id={`arrow-${k}`} viewBox="0 0 10 10" refX="9" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse">
              {k === "filled" && <path d="M0,1 L9,5 L0,9 Z" fill="currentColor" />}
              {k === "hollow" && <path d="M0,1 L9,5 L0,9 Z" fill="var(--node-fill)" stroke="currentColor" />}
              {k === "chevron" && <path d="M1,1 L8,5 L1,9" fill="none" stroke="currentColor" strokeWidth="1.4" />}
              {k === "double" && <path d="M0,1 L4,5 L0,9 Z M5,1 L9,5 L5,9 Z" fill="currentColor" />}
            </marker>
          ))}
        </defs>
        <g transform={`translate(${cx},${cy})`}>
          <g style={{ opacity: turning ? 0 : 1, transition: "opacity 200ms" }}>
            {sectors.map((s) => (
              <g key={s.id}>
                <path id={`sector-arc-${s.id}`} d={arcPath(outerR + (mobile ? 10 : 14), s.start, s.end)} fill="none" />
                <text className={mobile ? "sector-label sector-label-sm" : "sector-label"} style={{ fill: sectorVar(s.id) }}>
                  <textPath href={`#sector-arc-${s.id}`} startOffset="50%" textAnchor="middle">{mobile && t.has(`sectorsShort.${s.id}`) ? t(`sectorsShort.${s.id}`) : isRtl ? s.label.ar : s.label.en}</textPath>
                </text>
              </g>
            ))}
            {!mobile && [...pills, ...bands].map((rg) => {
              const text = isRtl ? rg.label.ar : rg.label.en;
              const lr = labelRadius(rg.radius, rg.labelSide, rg.thickness);
              const f = fitRuler(lr, rg.start, rg.end, text);
              return (
                <g key={rg.id}>
                  <path id={`ring-${rg.id}`} d={arcPath(lr, f.start, f.end)} fill="none" />
                  <text className="ring-label"><textPath href={`#ring-${rg.id}`} startOffset="50%" textAnchor="middle">{text}</textPath></text>
                </g>
              );
            })}
          </g>

          {/* hierarchy fan: dotted lines from the focused body to its children and from its parent */}
          {selected && snapshot.nodes[selected] && (() => {
            const n = snapshot.nodes[selected];
            const bodyId = n.type === "dept_head" || n.type === "seat" ? n.headOf : n.id;
            const body = bodyId ? snapshot.nodes[bodyId] : null;
            if (!body || !placed[body.id]) return null;
            // a seat only ties back to its chamber; a body fans to its children and parent
            const links = n.type === "seat" ? [[body.id, n.id] as const] : [...(body.children ?? []).map((c) => [body.id, c] as const), ...(body.parent ? [[body.parent, body.id] as const] : [])];
            return links.map(([a, b]) => placed[a] && placed[b] && (
              <g key={`fan-${a}-${b}`} style={{ opacity: turning ? 0 : tip?.kind === "fan" && tip.id === `${a}|${b}` ? 1 : 0.7, transition: "opacity 200ms" }}>
                <line x1={placed[a].x} y1={placed[a].y} x2={placed[b].x} y2={placed[b].y} stroke={sectorVar(body.sector)} strokeWidth={tip?.kind === "fan" && tip.id === `${a}|${b}` ? 2 : 1} strokeDasharray="2 3" />
                <line x1={placed[a].x} y1={placed[a].y} x2={placed[b].x} y2={placed[b].y} stroke="transparent" strokeWidth={12} style={{ pointerEvents: turning ? "none" : "stroke" }}
                  onMouseEnter={(ev) => { const q = point(ev); setTip({ kind: "fan", id: `${a}|${b}`, x: q.x, y: q.y - 10 }); }} onMouseLeave={() => setTip(null)} />
              </g>
            ));
          })()}

          {/* edges: hidden while the wheel turns, attached once it settles */}
          {edges.filter((e) => headShown(snapshot.nodes[e.fromId]) && headShown(snapshot.nodes[e.toId])).map((e) => {
            const touches = focusId !== null && (e.fromId === focusId || e.toId === focusId);
            const hoverable = selected ? e.fromId === selected || e.toId === selected : showAllEdges;
            const isHovered = hoverable && hoveredEdge === e.id;
            const verb = t.has(`edges.${e.type}`) ? t(`edges.${e.type}`) : e.type;
            const label = isHovered ? (e.seatsAppointed > 1 ? `${verb} · ${e.seatsAppointed}` : verb) : null;
            const alpha = turning ? 0 : edgeAlpha(e);
            return <EdgePath key={e.id} edge={e} from={placed[e.fromId]} to={placed[e.toId]} opacity={alpha} highlighted={(!!selected && touches) || isHovered} hovered={isHovered} arrows={touches || showAllEdges || isHovered} label={label} rtl={isRtl} hoverable={hoverable && !turning} touch={mobile}
              onHover={(ev) => { if (mobile) return; if (!ev) return setTip(null); const q = point(ev); setTip({ kind: "edge", id: e.id, x: q.x, y: q.y - 10 }); }} onTap={() => tapEdge(e.id)} />;
          })}

          {/* centre seal */}
          {center && placed[center.id] && (
            <g style={{ cursor: "pointer", pointerEvents: "auto" }} onClick={(ev) => { ev.stopPropagation(); tapNode(center.id); }} onMouseEnter={() => { if (!mobile) setHover(center.id); }} onMouseLeave={() => { if (!mobile) setHover(null); }}>
              <path d={seal((mobile ? 0.5 : 0.66) * unit, 16)} fill="var(--c-constituency)" fillOpacity={0.55} stroke="var(--c-constituency)" strokeWidth={selected === center.id ? 2 : 1} />
              <text textAnchor="middle" style={{ fill: "var(--c-constituency)", fontSize: mobile ? 10 : 12.5, fontWeight: 700 }}>
                {wrap(localized(center.name, locale), 16).map((line, i, arr) => <tspan key={i} x={0} y={(i - (arr.length - 1) / 2) * (mobile ? 11 : 13) + 4}>{line}</tspan>)}
              </text>
            </g>
          )}
        </g>
      </svg>
      {tip && !mobile && <Tooltip tip={tip} snapshot={snapshot} locale={locale} t={t} rtl={isRtl} width={size.width} />}
    </div>
  );
}

function Tooltip({ tip, snapshot, locale, t, rtl, width }: { tip: { kind: "node" | "edge" | "fan"; id: string; x: number; y: number }; snapshot: GraphSnapshot; locale: string; t: ReturnType<typeof useTranslations>; rtl: boolean; width: number }) {
  void rtl;
  const maxW = tip.kind === "node" ? 240 : 320;
  const half = maxW / 2;
  const x = Math.min(Math.max(tip.x, half + 8), Math.max(half + 8, width - half - 8));
  const below = tip.y < 120;
  const style: React.CSSProperties = {
    left: x, top: below ? tip.y + 14 : tip.y, width: "max-content", maxWidth: maxW,
    transform: below ? "translate(-50%, 0)" : "translate(-50%, calc(-100% - 10px))",
  };
  if (tip.kind === "node") {
    const n = snapshot.nodes[tip.id];
    if (!n) return null;
    const holder = n.people.find((p) => p.name);
    return (
      <div className="pointer-events-none absolute z-20 card px-3 py-2 text-xs shadow-lg" style={style}>
        <div className="font-semibold text-sm" style={{ color: `var(--c-${n.sector ?? "constituency"})` }}>{localized(n.name, locale)}</div>
        {n.subtype && t.has(`subtypes.${n.subtype}`) && <div className="text-ink-3">{t(`subtypes.${n.subtype}`)}</div>}
        {holder?.name && <div className="text-ink-2 mt-0.5">{localized(holder.name, locale)}</div>}
      </div>
    );
  }
  if (tip.kind === "fan") {
    const [parentId, childId] = tip.id.split("|");
    const parent = snapshot.nodes[parentId], child = snapshot.nodes[childId];
    if (!parent || !child) return null;
    return (
      <div className="pointer-events-none absolute z-20 card px-3 py-2.5 text-xs shadow-lg border-s-2" style={{ ...style, borderColor: "var(--ink-3)" }}>
        <div className="mono-label mb-1">{t("nav.hierarchy")}</div>
        <div className="text-[13px]"><span className="font-semibold">{localized(child.name, locale)}</span> <span className="text-ink-3">{t("panel.parentVerb")}</span> <span className="font-semibold">{localized(parent.name, locale)}</span></div>
        <div className="text-ink-2 mt-1 leading-snug">{t("panel.hierarchyHelp")}</div>
      </div>
    );
  }
  const e = snapshot.edges[tip.id];
  if (!e) return null;
  const verb = t.has(`edges.${e.type}`) ? t(`edges.${e.type}`) : e.type;
  const help = t.has(`edgeHelp.${e.type}`) ? t(`edgeHelp.${e.type}`) : null;
  const family = t.has(`edgeFamilies.${edgeFamily[e.type] ?? "appointment"}`) ? t(`edgeFamilies.${edgeFamily[e.type] ?? "appointment"}`) : null;
  const color = familyColor[edgeFamily[e.type] ?? "appointment"];
  const cite = typeof e.metadata?.cite === "string" ? (e.metadata.cite as string) : e.legalSource ? localized(e.legalSource.title, locale) : null;
  const note = typeof e.metadata?.note === "string" ? (e.metadata.note as string) : null;
  return (
    <div className="pointer-events-none absolute z-20 card px-3 py-2.5 text-xs shadow-lg border-s-2" style={{ ...style, borderColor: color }}>
      {family && <div className="mono-label mb-1" style={{ color }}>{family}</div>}
      <div className="text-[13px]"><span className="font-semibold">{localized(snapshot.nodes[e.fromId]?.name, locale)}</span> <span style={{ color }}>{verb}</span> <span className="font-semibold">{localized(snapshot.nodes[e.toId]?.name, locale)}</span>{e.seatsAppointed > 1 && <span className="text-ink-3"> · {t("panel.seats", { count: e.seatsAppointed })}</span>}</div>
      {help && <div className="text-ink-2 mt-1 leading-snug">{help}</div>}
      {note && <div className="text-ink-3 mt-1 leading-snug">{note}</div>}
      {cite && <div className="text-ink-3 mt-1">{cite}</div>}
    </div>
  );
}

function EdgePath({ edge, from, to, opacity, highlighted, hovered = false, arrows, label, rtl, hoverable = true, touch = false, onHover, onTap }: { edge: GraphEdge; from: Placed; to: Placed; opacity: number | string; highlighted: boolean; hovered?: boolean; arrows: boolean; label: string | null; rtl: boolean; hoverable?: boolean; touch?: boolean; onHover: (ev: React.MouseEvent | null) => void; onTap?: () => void }) {
  const s = edgeStyle[edge.type] ?? { arrow: "none" as const };
  const color = familyColor[edgeFamily[edge.type] ?? "appointment"];
  const dx = to.x - from.x, dy = to.y - from.y;
  const len = Math.hypot(dx, dy) || 1;
  const ux = dx / len, uy = dy / len;
  const x1 = from.x + ux * (from.r + 3), y1 = from.y + uy * (from.r + 3);
  const x2 = to.x - ux * (to.r + 4), y2 = to.y - uy * (to.r + 4);
  const mx = (x1 + x2) / 2 - (s.curved ? dy * 0.18 : 0), my = (y1 + y2) / 2 + (s.curved ? dx * 0.18 : 0);
  const d = s.curved ? `M${x1},${y1} Q${mx},${my} ${x2},${y2}` : `M${x1},${y1} L${x2},${y2}`;
  const lx = s.curved ? 0.25 * x1 + 0.5 * mx + 0.25 * x2 : (x1 + x2) / 2;
  const ly = s.curved ? 0.25 * y1 + 0.5 * my + 0.25 * y2 : (y1 + y2) / 2;
  return (
    <g style={{ opacity, transition: "opacity 150ms" }}>
      {hovered && <path d={d} fill="none" stroke={color} strokeWidth={9} strokeOpacity={0.22} strokeLinecap="round" />}
      <path d={d} fill="none" stroke={highlighted ? color : "var(--ink-3)"} strokeWidth={hovered ? 2.4 : highlighted ? 1.6 : 1} strokeDasharray={s.dash} markerEnd={arrows && s.arrow !== "none" ? `url(#arrow-${s.arrow})` : undefined} style={highlighted ? { color } : undefined} />
      {hoverable && <path d={d} fill="none" stroke="transparent" strokeWidth={touch ? 26 : 12} style={{ pointerEvents: "stroke" }} onMouseEnter={(ev) => onHover(ev)} onMouseLeave={() => onHover(null)} onClick={(ev) => { if (!touch) return; ev.stopPropagation(); onTap?.(); }} />}
      {label && (
        <g transform={`translate(${lx},${ly})`} style={{ pointerEvents: "none" }}>
          <rect x={-(label.length * 3.6 + 10)} y={-11} width={label.length * 7.2 + 20} height={22} rx={6} fill="var(--card)" stroke={color} strokeOpacity={0.6} />
          <text className="edge-label" textAnchor="middle" dominantBaseline="middle" style={{ fill: color, direction: rtl ? "rtl" : "ltr", stroke: "none" }}>{label}</text>
        </g>
      )}
    </g>
  );
}

function pt(r: number, a: number) { return `${Math.cos(a) * r},${Math.sin(a) * r}`; }
/** Ruler arcs must be at least as long as their label (≈6.6px per glyph at 10px with tracking). */
const labelRadius = (r: number, side: "inside" | "outside", thickness: number) => (side === "outside" ? r + thickness / 2 + 12 : r - thickness / 2 - 12);
function fitRuler(r: number, start: number, end: number, text: string): { start: number; end: number } {
  const need = (text.length * 7.8 + 28) / Math.max(60, r);
  const mid = (start + end) / 2;
  const half = Math.max((end - start) / 2, need / 2);
  return { start: mid - half, end: mid + half };
}
function wedge(r0: number, r1: number, a0: number, a1: number): string {
  const large = a1 - a0 > Math.PI ? 1 : 0;
  return `M${pt(r0, a0)} L${pt(r1, a0)} A${r1},${r1} 0 ${large} 1 ${pt(r1, a1)} L${pt(r0, a1)} A${r0},${r0} 0 ${large} 0 ${pt(r0, a0)} Z`;
}
function arcPath(r: number, a0: number, a1: number): string {
  // text along the arc reads left-to-right on the top half; flip direction on the bottom half so it stays upright
  const mid = (a0 + a1) / 2;
  const bottom = Math.sin(mid) > 0;
  const large = a1 - a0 > Math.PI ? 1 : 0;
  return bottom ? `M${pt(r, a1)} A${r},${r} 0 ${large} 0 ${pt(r, a0)}` : `M${pt(r, a0)} A${r},${r} 0 ${large} 1 ${pt(r, a1)}`;
}
function arcBand(r: number, a0: number, a1: number, thickness: number): string { return wedge(r - thickness / 2, r + thickness / 2, a0, a1); }
function seal(r: number, n: number): string {
  const pts: string[] = [];
  for (let i = 0; i < n * 2; i++) {
    const a = (i * Math.PI) / n - Math.PI / 2;
    const rr = i % 2 === 0 ? r : r * 0.88;
    pts.push(pt(rr, a));
  }
  return `M${pts.join(" L")} Z`;
}
function wrap(s: string, max: number): string[] {
  const words = s.split(" "); const lines: string[] = []; let cur = "";
  for (const w of words) { if ((cur + " " + w).trim().length > max && cur) { lines.push(cur); cur = w; } else cur = (cur + " " + w).trim(); }
  if (cur) lines.push(cur);
  return lines;
}
