export type Localized = { ar: string | null; en: string | null; fr?: string | null };
export type Sector = "legislative" | "executive" | "judicial" | "independent" | "local";
export type NodeType = "constituency" | "elected" | "department" | "commission" | "advisory" | "dept_head" | "seat";

export interface LegalSource {
  kind: string;
  number: string | null;
  date: string | null;
  title: Localized;
  inForce: boolean;
  gazetteIssue: string | null;
  gazetteUrl: string | null;
  url: string | null;
}

export interface PersonRef {
  id: string | null;
  name: Localized | null;
  positionId: string;
  positionName: Localized;
  status: string;
  vacancyReason: string | null;
  startedAt: string | null;
  party: string | null;
  bloc: string | null;
  imageUrl: string | null;
  legalSource: LegalSource | null;
}

export interface GraphNode {
  id: string;
  type: NodeType;
  subtype?: string | null;
  legalForm?: string | null;
  kind?: string;
  sector: Sector | null;
  name: Localized;
  description: Localized;
  aliases: { ar: string[]; en: string[]; fr: string[] };
  officialUrl?: string | null;
  legalSource: LegalSource | null;
  parent?: string | null;
  level?: number;
  children?: string[];
  head?: string | null;
  headOf?: string | null;
  positions?: string[];
  seatsCount?: number;
  functions?: string[] | null;
  stateFunded?: boolean | null;
  ownership?: Record<string, unknown> | null;
  status: string;
  statusNote?: string | null;
  layoutHints?: Record<string, unknown> | null;
  grade?: string | null;
  appointingAuthority?: string | null;
  confession?: string | null;
  confessionBasis?: string | null;
  confessionSourceUrl?: string | null;
  seat?: { majorDistrict: string | null; minorDistrict: string | null; ordinal: number | null } | null;
  termYears?: number | null;
  people: PersonRef[];
  edges: string[];
  connectedNodes: string[];
}

export interface GraphEdge {
  id: string;
  type: string;
  fromId: string;
  toId: string;
  seatsAppointed: number;
  metadata: Record<string, unknown>;
  legalSource: LegalSource | null;
}

export interface LayoutDescriptor {
  id: string;
  centerNodeId: string;
  sectors: { id: Sector; label: { en: string; ar: string }; minAngleDeg: number }[];
  sectorGapDeg: number;
  rings: { bucket: string; spacing: number; label: string; sizeMetric: string }[];
  pills: { id: string; label: { en: string; ar: string }; sector: Sector; bodyNodeId: string; memberNodeType: string; memberKinds?: string[]; spacing?: number; spanDeg?: number; thickness: number; labelSide?: "inside" | "outside" }[];
  bands: { id: string; label: { en: string; ar: string }; sector: Sector; match: Record<string, unknown>; radius: number; outerEdgeWhen?: Record<string, unknown>; labelSide?: "inside" | "outside" }[];
  apexNodeIds?: string[];
  boundaryNodeIds?: Record<string, Sector[]>;
  sectorAliases?: Record<string, Sector>;
}

export interface GraphSnapshot {
  gov: string;
  generatedAt: string;
  layout: LayoutDescriptor;
  counts: { byType: Record<string, number>; bySector: Record<string, number>; vacantSeats: number; actingOfficials: number };
  nodes: Record<string, GraphNode>;
  edges: Record<string, GraphEdge>;
}

export interface NodeDetail {
  node: GraphNode;
  edges: GraphEdge[];
  connected: { id: string; type: NodeType; subtype: string | null; sector: Sector | null; name: Localized }[];
}

export function localized(l: Localized | null | undefined, locale: string): string {
  if (!l) return "";
  const v = (l as Record<string, string | null | undefined>)[locale];
  return v || l.en || l.ar || "";
}
