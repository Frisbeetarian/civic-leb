import { getTranslations } from "next-intl/server";
import type { GraphSnapshot } from "@/lib/types";

const typeDot: Record<string, string> = { constituency: "var(--c-constituency)", elected: "var(--c-legislative)", department: "var(--c-executive)", commission: "var(--c-independent)", advisory: "var(--c-independent)", dept_head: "var(--c-executive)", seat: "var(--c-legislative)" };

export async function OverviewCard({ counts, nodeCount }: { counts: GraphSnapshot["counts"]; nodeCount: number }) {
  const t = await getTranslations();
  const types = Object.entries(counts.byType).sort(([, a], [, b]) => b - a);
  const sectors = Object.entries(counts.bySector).filter(([k]) => k !== "none");
  return (
    <section className="card p-6">
      <h2 className="text-[20px] font-semibold">{t("overview.title")}</h2>
      <p className="mt-1 text-[16px] text-ink-2">{t("overview.subtitle")}</p>
      <div className="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-6 text-[16px]">
        <div>
          <h3 className="font-semibold mb-2">{t("overview.byType")}</h3>
          <ul className="divide-y divide-line">
            {types.map(([k, v]) => (
              <li key={k} className="flex items-center gap-2 py-2"><span className="w-2.5 h-2.5 rounded-full" style={{ background: typeDot[k] ?? "var(--ink-3)" }} /><span className="flex-1">{t.has(`types.${k}`) ? t(`types.${k}`) : k}</span><span className="tabular-nums">{v}</span></li>
            ))}
          </ul>
        </div>
        <div>
          <h3 className="font-semibold mb-2">{t("overview.bySector")}</h3>
          <ul className="divide-y divide-line">
            {sectors.map(([k, v]) => (
              <li key={k} className="flex items-center gap-2 py-2"><span className="w-2.5 h-2.5 rounded-full" style={{ background: `var(--c-${k})` }} /><span className="flex-1">{t.has(`sectors.${k}`) ? t(`sectors.${k}`) : k}</span><span className="tabular-nums">{v}</span></li>
            ))}
          </ul>
        </div>
      </div>
      <p className="mt-4 pt-3 border-t border-line text-sm text-ink-3">{t("overview.nodes", { count: nodeCount })}</p>
    </section>
  );
}
