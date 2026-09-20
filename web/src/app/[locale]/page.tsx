import { setRequestLocale, getTranslations } from "next-intl/server";
import { fetchGraph } from "@/lib/graph";
import { LatestChanges } from "@/components/LatestChanges";
import { OverviewCard } from "@/components/OverviewCard";


export default async function Home({ params }: { params: Promise<{ locale: string }> }) {
  const { locale } = await params;
  setRequestLocale(locale);
  const t = await getTranslations();
  const snapshot = await fetchGraph();
  return (
    <>
      <LatestChanges />
      <OverviewCard counts={snapshot.counts} nodeCount={Object.keys(snapshot.nodes).length} />
      <section className="card p-6">
        <h2 className="text-[20px] font-semibold">{t("about.title")}</h2>
        <p className="mt-2 text-[16px] text-ink-2 leading-relaxed">{t("site.about")}</p>
        <div className="mt-4 pt-4 border-t border-line flex items-center gap-4 text-sm text-ink-3">
          <span>{t("site.builtBy")}</span>
          <span className="flex-1" />
          <a className="link" href="https://github.com/civicleb/civicleb" target="_blank" rel="noreferrer">GitHub</a>
        </div>
      </section>
    </>
  );
}
