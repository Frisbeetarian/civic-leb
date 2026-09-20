/**
 * Node glyphs in CivLab's geometry (21-unit viewBox scaled to radius r): white base, colour at 50%,
 * 1px colour stroke. Kinds map to our subtypes; dashed stroke for bodies that only exist on paper.
 */
export function Glyph({ kind, r, color, fillAlpha = 0.5, dashed = false, selected = false }: { kind: string; r: number; color: string; fillAlpha?: number; dashed?: boolean; selected?: boolean }) {
  const s = r / 7; // CivLab shapes are drawn around radius 7 in a 21-unit box centred at (10.5, 11)
  const stroke = { stroke: color, strokeWidth: selected ? 2.2 / s : 1.1 / s, strokeDasharray: dashed ? `${2.5 / s} ${2 / s}` : undefined, vectorEffect: "non-scaling-stroke" as const };
  const layers = (shape: (p: Record<string, unknown>) => React.ReactNode) => (
    <g transform={`scale(${s}) translate(-10.5,-11)`}>
      {shape({ fill: "var(--node-fill)" })}
      {shape({ fill: color, fillOpacity: fillAlpha })}
      {shape({ fill: "none", ...stroke })}
    </g>
  );
  switch (kind) {
    case "constituency":
    case "elected":
      return layers((p) => <circle cx={10.5} cy={11} r={7} {...p} />);
    case "commission":
    case "council":
    case "oversight":
      return layers((p) => <rect x={2.51} y={11} width={11.3} height={11.3} rx={4.08} transform="rotate(-45 2.51 11)" {...p} />);
    case "advisory":
      return layers((p) => <path d="M9.75 4.51c.46-.27 1.04-.27 1.5 0l4.5 2.6c.46.26.75.76.75 1.29v5.2c0 .53-.29 1.03-.75 1.3l-4.5 2.6c-.46.26-1.04.26-1.5 0l-4.5-2.6a1.5 1.5 0 0 1-.75-1.3V8.4c0-.53.29-1.03.75-1.3l4.5-2.6Z" {...p} />);
    case "court":
    case "confessional_court":
      return layers((p) => <polygon points="10.5,4 17.16,8.84 14.61,16.66 6.39,16.66 3.84,8.84" {...p} />);
    case "state_company":
      return (
        <g>{layers((p) => <rect x={4.67} y={4.67} width={11.66} height={11.66} rx={3.32} {...p} />)}
          <g transform={`scale(${s}) translate(-10.5,-11)`}><rect x={7.17} y={7.17} width={6.66} height={6.66} rx={1.66} fill="none" {...stroke} /></g></g>
      );
    case "regulator":
      return layers((p) => <polygon points="13.18,4.53 16.97,8.32 16.97,13.68 13.18,17.47 7.82,17.47 4.03,13.68 4.03,8.32 7.82,4.53" {...p} />);
    case "security_service":
      return layers((p) => <path d="M10.5 3.5 17 6.2v4.6c0 3.4-2.8 6.2-6.5 7.7C6.8 17 4 14.2 4 10.8V6.2l6.5-2.7Z" {...p} />);
    case "dept_head":
    case "seat":
      return (
        <g transform={`scale(${s}) translate(-10.5,-11)`}>
          <rect x={4.25} y={8} width={12.5} height={10} rx={2.6} fill="var(--node-fill)" />
          <rect x={4.25} y={8} width={12.5} height={10} rx={2.6} fill={color} fillOpacity={fillAlpha} />
          <rect x={4.25} y={8} width={12.5} height={10} rx={2.6} fill="none" {...stroke} />
          <circle cx={10.5} cy={8} r={3.75} fill="var(--node-fill)" />
          <circle cx={10.5} cy={8} r={3.75} fill={color} fillOpacity={fillAlpha} />
          <circle cx={10.5} cy={8} r={3.75} fill="none" {...stroke} />
        </g>
      );
    default:
      return layers((p) => <rect x={4.67} y={4.67} width={11.66} height={11.66} rx={3.32} {...p} />);
  }
}

export function glyphKind(n: { type: string; subtype?: string | null }): string {
  if (n.type === "department") {
    if (n.subtype === "court" || n.subtype === "confessional_court" || n.subtype === "state_company" || n.subtype === "security_service") return n.subtype;
    return "department";
  }
  if (n.type === "commission") {
    if (n.subtype === "regulator" || n.subtype === "oversight" || n.subtype === "council") return n.subtype;
    return "commission";
  }
  return n.type;
}
