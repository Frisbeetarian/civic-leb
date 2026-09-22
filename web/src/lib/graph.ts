import "server-only";
import type { GraphSnapshot, NodeDetail } from "./types";

/**
 * Data access with two modes:
 *  - live: API_URL is set and the Laravel API serves the published snapshot (cached 60s);
 *  - static: no API_URL, the snapshot bundled at public/lb-graph.json is used, so the site can be
 *    exported and hosted with no backend (decisions.md: static preview on Cloudflare).
 * Pages read the snapshot on the server only; the browser fetches it once from snapshotUrl() and
 * keeps it for the session, so no page embeds the whole graph.
 */
// the static export always uses the bundled file, even when .env.local points dev at a local API:
// otherwise the browser would be sent to that dev URL from the deployed site
const API = process.env.STATIC_EXPORT === "1" ? undefined : process.env.API_URL;

async function bundled(): Promise<GraphSnapshot> {
  const mod = await import("../../public/lb-graph.json");
  return mod.default as unknown as GraphSnapshot;
}

/** Where the browser fetches the same snapshot; the version query defeats a stale cached copy after a deploy. */
export function snapshotUrl(snapshot: GraphSnapshot): string {
  return API ? `${API}/api/lb/graph` : `/lb-graph.json?v=${encodeURIComponent(snapshot.generatedAt)}`;
}

export async function fetchGraph(): Promise<GraphSnapshot> {
  if (!API) return bundled();
  const res = await fetch(`${API}/api/lb/graph`, { next: { revalidate: 60 } });
  if (!res.ok) throw new Error(`Graph API ${res.status}`);
  return res.json();
}

/** Same shape as GET /api/lb/nodes/{slug}, derived from a snapshot. */
export function nodeDetailFrom(snapshot: GraphSnapshot, slug: string): NodeDetail | null {
  const node = snapshot.nodes[slug];
  if (!node) return null;
  const ids = new Set<string>([
    ...node.connectedNodes, ...(node.children ?? []), ...(node.positions ?? []),
    ...[node.parent, node.head, node.headOf].filter((x): x is string => !!x),
  ]);
  const edges = node.edges.map((id) => snapshot.edges[id]).filter(Boolean);
  const connected = [...ids].map((id) => snapshot.nodes[id]).filter(Boolean).map((n) => ({ id: n.id, type: n.type, subtype: n.subtype ?? null, sector: n.sector, name: n.name, seat: n.seat ?? null, confession: n.confession ?? null }));
  return { node, edges, connected };
}

export async function fetchNode(slug: string): Promise<NodeDetail | null> {
  if (!API) return nodeDetailFrom(await bundled(), slug);
  const res = await fetch(`${API}/api/lb/nodes/${encodeURIComponent(slug)}`, { next: { revalidate: 60 } });
  if (res.status === 404) return null;
  if (!res.ok) throw new Error(`Node API ${res.status}`);
  return res.json();
}
