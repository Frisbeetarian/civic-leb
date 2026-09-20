import type { Sector, NodeType } from "./types";

// Own palette (decisions.md Q27): cedar green for the state, terracotta, ochre, teal, indigo.
export const sectorColor: Record<Sector, string> = {
  legislative: "var(--c-legislative)",
  executive: "var(--c-executive)",
  judicial: "var(--c-judicial)",
  independent: "var(--c-independent)",
  local: "var(--c-local)",
};

export const typeRadius: Record<NodeType, number> = {
  constituency: 16,
  elected: 11,
  department: 8,
  commission: 8,
  advisory: 7,
  dept_head: 5,
  seat: 3.5,
};

export const edgeStyle: Record<string, { dash?: string; arrow: "filled" | "double" | "hollow" | "chevron" | "none"; curved?: boolean }> = {
  elects: { arrow: "double" },
  appoints: { arrow: "filled" },
  confirms: { arrow: "hollow" },
  dept_head: { dash: "4 2", arrow: "none" },
  office: { dash: "1.5 3", arrow: "none" },
  ex_officio: { dash: "1.5 3", arrow: "none" },
  oversees: { arrow: "chevron", curved: true },
  advises: { arrow: "chevron", curved: true, dash: "3 3" },
  administers: { arrow: "chevron", curved: true },
  tutelage: { arrow: "chevron", curved: true },
  owns: { arrow: "filled", dash: "6 3" },
  inspects: { arrow: "chevron", curved: true, dash: "2 2" },
  prosecutes: { arrow: "filled", curved: true },
  reviews: { arrow: "hollow", curved: true },
  commands: { arrow: "filled" },
  regulates: { arrow: "chevron", curved: true, dash: "2 2" },
  disciplines: { arrow: "chevron", curved: true, dash: "2 2" },
  refers_to: { arrow: "hollow", curved: true, dash: "3 3" },
};

export type EdgeFamily = "election" | "appointment" | "hierarchy" | "oversight" | "tutelage" | "judicial" | "command";

export const edgeFamily: Record<string, EdgeFamily> = {
  elects: "election", confirms: "election",
  appoints: "appointment",
  dept_head: "hierarchy", office: "hierarchy", ex_officio: "hierarchy", administers: "hierarchy",
  oversees: "oversight", inspects: "oversight", disciplines: "oversight", regulates: "oversight", reviews: "oversight", advises: "oversight", refers_to: "oversight",
  tutelage: "tutelage", owns: "tutelage",
  prosecutes: "judicial",
  commands: "command",
};

export const familyColor: Record<EdgeFamily, string> = {
  election: "var(--c-legislative)",
  appointment: "var(--ink-2)",
  hierarchy: "var(--ink-3)",
  oversight: "var(--c-independent)",
  tutelage: "var(--c-executive)",
  judicial: "var(--c-judicial)",
  command: "var(--c-executive)",
};

export const familyOrder: EdgeFamily[] = ["election", "appointment", "hierarchy", "oversight", "tutelage", "judicial", "command"];
