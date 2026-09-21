"use client";
import { createContext, useContext, useEffect, useMemo, useState } from "react";
import { usePathname } from "next/navigation";
import { useLocale, useTranslations } from "next-intl";
import { Link, useRouter } from "@/i18n/navigation";
import type { GraphSnapshot } from "@/lib/types";
import { GraphView } from "./GraphView";
import { Legend } from "./Legend";
import { SearchModal } from "./SearchModal";
import { ThemeToggle } from "./ThemeToggle";
import { Logomark } from "./Logomark";

const GraphContext = createContext<GraphSnapshot | null>(null);
export const useGraph = () => {
  const g = useContext(GraphContext);
  if (!g) throw new Error("useGraph outside Shell");
  return g;
};

/** Selected node id from the URL (/{locale}/n/{slug}); the graph stays mounted across pages like CivLab. */
export function useSelectedSlug(): string | null {
  const pathname = usePathname();
  const m = pathname.match(/\/n\/([^/?#]+)/);
  return m ? decodeURIComponent(m[1]) : null;
}

function useIsMobile(): boolean {
  const [mobile, setMobile] = useState(false);
  useEffect(() => {
    const mq = window.matchMedia("(max-width: 1023px)");
    const apply = () => setMobile(mq.matches);
    apply();
    mq.addEventListener("change", apply);
    return () => mq.removeEventListener("change", apply);
  }, []);
  return mobile;
}

export function Shell({ snapshot, children }: { snapshot: GraphSnapshot; children: React.ReactNode }) {
  const t = useTranslations();
  const locale = useLocale();
  const router = useRouter();
  const urlSelected = useSelectedSlug();
  const mobile = useIsMobile();
  // Optimistic selection: the wheel starts turning on click, before the entity page's server round trip
  // lands; once the URL catches up the optimistic value is dropped.
  const [optimistic, setOptimistic] = useState<{ id: string | null; forUrl: string | null } | null>(null);
  const selected = optimistic && optimistic.forUrl === urlSelected ? optimistic.id : urlSelected;
  const node = selected ? snapshot.nodes[selected] : null;
  const select = (id: string | null) => {
    setOptimistic({ id, forUrl: urlSelected });
    router.push(id ? `/n/${id}` : "/");
  };
  const prefetch = (id: string | null) => { if (id) router.prefetch(`/n/${id}`); };
  const [hidden, setHidden] = useState<Set<string>>(new Set());
  const [showAllEdges, setShowAllEdges] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const toggleKind = (kind: string) => setHidden((h) => { const n = new Set(h); if (n.has(kind)) n.delete(kind); else n.add(kind); return n; });
  const sectorLabel = useMemo(() => (node?.sector ? t(`sectors.${node.sector}`) : null), [node, t]);
  // CivLab's prev/next arrows step through nodes of the selected node's type; with nothing selected they are history buttons
  const step = (dir: 1 | -1) => {
    if (!node) { if (dir === 1) router.forward(); else router.back(); return; }
    const same = Object.values(snapshot.nodes).filter((n) => n.type === node.type).sort((a, b) => (a.sector ?? "").localeCompare(b.sector ?? "") || (a.name.en ?? "").localeCompare(b.name.en ?? ""));
    const i = same.findIndex((n) => n.id === node.id);
    const next = same[(i + dir + same.length) % same.length];
    if (next) select(next.id);
  };
  const switchLocale = () => router.replace(selected ? `/n/${selected}` : "/", { locale: locale === "ar" ? "en" : "ar" });

  const headerCard = (
    <div className="flex gap-2">
      <div className="card flex-1 min-w-0 flex items-center gap-2 px-4 lg:px-6 h-12 text-[15px] lg:text-[16px]">
        <Link href="/" className="flex items-center gap-2 font-semibold shrink-0"><Logomark /> {t("site.name")}</Link>
        <span className="text-ink-3">/</span>
        <Link href="/" className={`shrink-0 ${node ? "text-ink-3" : "font-semibold"}`}>{t("site.govLabel")}</Link>
        {sectorLabel && <><span className="text-ink-3">/</span><span className="truncate">{sectorLabel}</span></>}
      </div>
      <button onClick={() => setSearchOpen(true)} className="card w-12 h-12 shrink-0 flex items-center justify-center hover:bg-hover" aria-label={t("nav.search")}>
        <svg width="18" height="18" viewBox="0 0 17 17" fill="none"><path d="M9.875 0.875C6.43 0.875 3.625 3.68 3.625 7.125c0 1.497.525 2.869 1.406 3.945L.05 16.05l.9.9 4.98-4.98a6.22 6.22 0 0 0 3.945 1.406c3.445 0 6.25-2.805 6.25-6.25S13.32.875 9.875.875Zm0 1.25c2.769 0 5 2.231 5 5s-2.231 5-5 5-5-2.231-5-5 2.231-5 5-5Z" fill="currentColor" /></svg>
      </button>
    </div>
  );

  const canvas = (
    <div className="relative h-[min(54vh,450px)] min-h-[350px] lg:h-screen lg:min-h-0 lg:max-h-none w-full overflow-hidden" style={{ background: "var(--canvas)" }}>
      {/* desktop chrome over the canvas */}
      <div className="absolute top-4 end-4 z-10 hidden lg:flex gap-2">
        <button onClick={switchLocale} className="card h-10 px-3 flex items-center text-sm hover:bg-hover">{t("nav.language")}</button>
        <ThemeToggle />
        <div className="card h-10 flex items-center">
          <button onClick={() => step(-1)} className="w-9 h-10 flex items-center justify-center hover:bg-hover rounded-s-2xl" aria-label="previous"><Arrow dir={locale === "ar" ? "right" : "left"} /></button>
          <button onClick={() => step(1)} className="w-9 h-10 flex items-center justify-center hover:bg-hover rounded-e-2xl" aria-label="next"><Arrow dir={locale === "ar" ? "left" : "right"} /></button>
        </div>
      </div>
      {/* mobile: header card floats over the top of the band */}
      <div className="absolute top-3 inset-x-3 z-10 lg:hidden">{headerCard}</div>
      <GraphView snapshot={snapshot} selected={selected} hidden={hidden} showAllEdges={showAllEdges} onSelect={select} onHoverNode={prefetch} mobile={mobile} />
      <div className="absolute bottom-4 start-4 z-10 hidden lg:block">
        <Legend hidden={hidden} onToggle={toggleKind} onReset={() => setHidden(new Set())} showAllEdges={showAllEdges} onToggleEdges={() => setShowAllEdges((v) => !v)} />
      </div>
      {node && (
        <div className="absolute bottom-3 lg:bottom-5 inset-x-0 flex justify-center pointer-events-none z-10">
          <span className="px-3 py-1 rounded-md text-sm font-semibold max-w-[80vw] truncate" style={{ color: node.sector ? `var(--c-${node.sector})` : "var(--brand)", background: "color-mix(in srgb, var(--card) 85%, transparent)" }}>
            {locale === "ar" ? node.name.ar : node.name.en}
          </span>
        </div>
      )}
      <div className="absolute bottom-4 end-4 z-10 card hidden lg:flex text-sm overflow-hidden">
        <span className="px-4 py-2 font-medium">{t("nav.graph")}</span>
        <span className="px-4 py-2 text-ink-3 bg-card-2">{t("nav.powerMap")}</span>
      </div>
    </div>
  );

  return (
    <GraphContext.Provider value={snapshot}>
      <div className="min-h-full w-full max-w-[100vw] overflow-x-hidden lg:overflow-visible lg:h-screen lg:grid lg:grid-cols-[560px_minmax(0,1fr)]">
        {/* mobile order: canvas, toolbar, cards. desktop: column, canvas. */}
        <div className="lg:hidden">{canvas}</div>
        <div className="lg:hidden flex items-center gap-2 px-3 pt-3 min-w-0 overflow-x-auto">
          <Legend hidden={hidden} onToggle={toggleKind} onReset={() => setHidden(new Set())} showAllEdges={showAllEdges} onToggleEdges={() => setShowAllEdges((v) => !v)} />
          <div className="card flex text-sm overflow-hidden h-9 items-stretch whitespace-nowrap"><span className="px-3 flex items-center font-medium">{t("nav.graph")}</span><span className="px-3 flex items-center text-ink-3 bg-card-2">{t("nav.powerMap")}</span></div>
          <div className="flex-1" />
          <button onClick={switchLocale} className="card h-9 px-3 flex items-center text-sm hover:bg-hover whitespace-nowrap">{t("nav.language")}</button>
          <div className="scale-90 origin-end shrink-0"><ThemeToggle /></div>
        </div>
        <div className="flex flex-col gap-3 lg:gap-4 p-3 lg:p-4 lg:overflow-y-auto min-w-0">
          <div className="hidden lg:block">{headerCard}</div>
          {children}
        </div>
        <div className="hidden lg:block">{canvas}</div>
      </div>
      {searchOpen && <SearchModal snapshot={snapshot} onClose={() => setSearchOpen(false)} onSelect={(id) => { setSearchOpen(false); select(id); }} />}
    </GraphContext.Provider>
  );
}

function Arrow({ dir }: { dir: "left" | "right" }) {
  return <svg width="18" height="18" viewBox="0 0 20 20" style={{ transform: dir === "right" ? "scaleX(-1)" : undefined }}><path d="M11.89 2.68 5.02 9.55 4.59 10l.43.45 6.87 6.87.9-.9L6.37 10l6.42-6.43-.9-.9Z" fill="currentColor" /></svg>;
}
