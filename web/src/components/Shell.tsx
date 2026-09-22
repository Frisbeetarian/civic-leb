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
import { edgeFamily, familyColor } from "@/lib/palette";

const GraphContext = createContext<GraphSnapshot | null>(null);
/** The snapshot once the browser has fetched it; null while loading (or if the fetch failed). */
export const useGraph = () => useContext(GraphContext);

/** Fetches the snapshot once per session; the layout keeps Shell mounted across pages so it never refetches on navigation. */
function useSnapshot(url: string): { snapshot: GraphSnapshot | null; failed: boolean; retry: () => void } {
  const [snapshot, setSnapshot] = useState<GraphSnapshot | null>(null);
  const [failed, setFailed] = useState(false);
  const [attempt, setAttempt] = useState(0);
  useEffect(() => {
    const ctrl = new AbortController();
    fetch(url, { signal: ctrl.signal })
      .then((r) => { if (!r.ok) throw new Error(`Graph ${r.status}`); return r.json() as Promise<GraphSnapshot>; })
      .then(setSnapshot)
      .catch((e: unknown) => { if ((e as { name?: string })?.name !== "AbortError") { console.error(e); setFailed(true); } });
    return () => ctrl.abort();
  }, [url, attempt]);
  return { snapshot, failed, retry: () => { setFailed(false); setAttempt((a) => a + 1); } };
}

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

export function Shell({ snapshotUrl, children }: { snapshotUrl: string; children: React.ReactNode }) {
  const t = useTranslations();
  const locale = useLocale();
  const router = useRouter();
  const urlSelected = useSelectedSlug();
  const mobile = useIsMobile();
  const { snapshot, failed, retry } = useSnapshot(snapshotUrl);
  // Optimistic selection: the wheel starts turning on click, before the entity page's server round trip
  // lands; once the URL catches up the optimistic value is dropped.
  const [optimistic, setOptimistic] = useState<{ id: string | null; forUrl: string | null } | null>(null);
  const selected = optimistic && optimistic.forUrl === urlSelected ? optimistic.id : urlSelected;
  const node = selected && snapshot ? snapshot.nodes[selected] ?? null : null;
  const select = (id: string | null) => {
    setOptimistic({ id, forUrl: urlSelected });
    router.push(id ? `/n/${id}` : "/");
  };
  const prefetch = (id: string | null) => { if (id) router.prefetch(`/n/${id}`); };
  const [hidden, setHidden] = useState<Set<string>>(new Set());
  const [showAllEdges, setShowAllEdges] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [preview, setPreview] = useState<string | null>(null);
  const [legendOpen, setLegendOpen] = useState(false);
  const previewNode = preview && snapshot ? snapshot.nodes[preview] ?? null : null;
  const [previewEdge, setPreviewEdge] = useState<string | null>(null);
  const previewEdgeObj = previewEdge && snapshot ? snapshot.edges[previewEdge] ?? null : null;
  const jumpToEdge = (id: string) => { const el = document.getElementById(`edge-${id}`); el?.scrollIntoView({ behavior: "smooth", block: "center" }); el?.classList.add("flash"); setTimeout(() => el?.classList.remove("flash"), 1600); };
  const toggleKind = (kind: string) => setHidden((h) => { const n = new Set(h); if (n.has(kind)) n.delete(kind); else n.add(kind); return n; });
  const sectorLabel = useMemo(() => (node?.sector ? t(`sectors.${node.sector}`) : null), [node, t]);
  // CivLab's prev/next arrows step through nodes of the selected node's type; with nothing selected they are history buttons
  const step = (dir: 1 | -1) => {
    if (!node || !snapshot) { if (dir === 1) router.forward(); else router.back(); return; }
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
      <button onClick={() => setLegendOpen((o) => !o)} className="card w-12 h-12 shrink-0 flex items-center justify-center hover:bg-hover lg:hidden" aria-label={t("nav.legend")} aria-expanded={legendOpen}>
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" strokeWidth="1.6"><circle cx="4.5" cy="4.5" r="2.5" /><rect x="11" y="2" width="5" height="5" rx="1.2" /><path d="M4.5 11l2.5 4.5h-5z" /><rect x="11" y="11" width="5" height="5" rx="1.2" transform="rotate(45 13.5 13.5)" /></svg>
      </button>
      <button onClick={() => setSearchOpen(true)} disabled={!snapshot} className="card w-12 h-12 shrink-0 flex items-center justify-center hover:bg-hover disabled:opacity-50" aria-label={t("nav.search")}>
        <svg width="18" height="18" viewBox="0 0 17 17" fill="none"><path d="M9.875 0.875C6.43 0.875 3.625 3.68 3.625 7.125c0 1.497.525 2.869 1.406 3.945L.05 16.05l.9.9 4.98-4.98a6.22 6.22 0 0 0 3.945 1.406c3.445 0 6.25-2.805 6.25-6.25S13.32.875 9.875.875Zm0 1.25c2.769 0 5 2.231 5 5s-2.231 5-5 5-5-2.231-5-5 2.231-5 5-5Z" fill="currentColor" /></svg>
      </button>
    </div>
  );

  const canvas = (
    <div className="relative h-[min(50vh,460px)] min-h-[340px] lg:h-screen lg:min-h-0 lg:max-h-none w-full overflow-hidden" style={{ background: "var(--canvas)" }}>
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
      {snapshot
        ? <GraphView snapshot={snapshot} selected={selected} hidden={hidden} showAllEdges={showAllEdges} onSelect={select} onHoverNode={prefetch} onPreview={setPreview} onPreviewEdge={setPreviewEdge} mobile={mobile} />
        : failed && (
          <div className="absolute inset-0 flex items-center justify-center">
            <div className="card px-4 py-3 text-sm flex items-center gap-3"><span className="text-ink-2">{t("nav.graphFailed")}</span><button onClick={retry} className="font-semibold hover:underline">{t("nav.retry")}</button></div>
          </div>
        )}
      {/* mobile legend panel, anchored under the header card */}
      {legendOpen && (
        <div className="absolute top-[68px] inset-x-3 z-20 lg:hidden" onClick={(e) => e.stopPropagation()}>
          <Legend hidden={hidden} onToggle={toggleKind} onReset={() => setHidden(new Set())} showAllEdges={showAllEdges} onToggleEdges={() => setShowAllEdges((v) => !v)} forceOpen onClose={() => setLegendOpen(false)} />
        </div>
      )}
      <div className="absolute bottom-4 start-4 z-10 hidden lg:block">
        <Legend hidden={hidden} onToggle={toggleKind} onReset={() => setHidden(new Set())} showAllEdges={showAllEdges} onToggleEdges={() => setShowAllEdges((v) => !v)} />
      </div>
      {previewEdgeObj && snapshot && (() => {
        const e = previewEdgeObj;
        const from = snapshot.nodes[e.fromId], to = snapshot.nodes[e.toId];
        const verb = t.has(`edges.${e.type}`) ? t(`edges.${e.type}`) : e.type;
        const help = t.has(`edgeHelp.${e.type}`) ? t(`edgeHelp.${e.type}`) : null;
        const cite = typeof e.metadata?.cite === "string" ? (e.metadata.cite as string) : null;
        const fam = edgeFamily[e.type] ?? "appointment";
        const color = familyColor[fam];
        const nm = (n: typeof from) => (n ? (locale === "ar" ? n.name.ar : n.name.en) : "");
        return (
          <div className="absolute bottom-3 inset-x-3 z-10 lg:hidden">
            <div className="rounded-xl shadow-lg p-3 text-sm border-s-2" style={{ background: "color-mix(in srgb, var(--card) 94%, transparent)", borderColor: color }}>
              <div className="mono-label mb-1" style={{ color }}>{t(`edgeFamilies.${fam}`)}</div>
              <div className="text-[14px]"><span className="font-semibold">{nm(from)}</span> <span style={{ color }}>{verb}</span> <span className="font-semibold">{nm(to)}</span>{e.seatsAppointed > 1 && <span className="text-ink-3"> · {t("panel.seats", { count: e.seatsAppointed })}</span>}</div>
              {help && <div className="text-xs text-ink-2 mt-1 leading-snug">{help}</div>}
              {cite && <div className="text-xs text-ink-3 mt-1">{cite}</div>}
              {node && (e.fromId === node.id || e.toId === node.id) && <button onClick={() => jumpToEdge(e.id)} className="mt-2 text-xs underline underline-offset-2 text-ink-2">{t("nav.seeInConnections")}</button>}
            </div>
          </div>
        );
      })()}
      {!previewEdgeObj && (previewNode ?? node) && (() => {
        const n = previewNode ?? node!;
        const holder = n.people.find((p) => p.name);
        const isPreview = !!previewNode;
        return (
          <div className={`absolute bottom-3 lg:bottom-5 inset-x-0 flex justify-center z-10 ${isPreview ? "" : "pointer-events-none"}`}>
            <button onClick={() => isPreview && select(n.id)} className={`px-3 py-1.5 rounded-lg text-sm font-semibold max-w-[86vw] text-start ${isPreview ? "shadow-lg" : ""}`} style={{ color: n.sector ? `var(--c-${n.sector})` : "var(--brand)", background: "color-mix(in srgb, var(--card) 92%, transparent)" }}>
              <span className="block truncate">{locale === "ar" ? n.name.ar : n.name.en}</span>
              {isPreview && holder?.name && <span className="block text-xs font-normal text-ink-2 truncate">{locale === "ar" ? holder.name.ar : holder.name.en}</span>}
              {isPreview && <span className="block text-[11px] font-normal text-ink-3">{t("nav.tapToOpen")}</span>}
            </button>
          </div>
        );
      })()}
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
        <div className="lg:hidden flex items-center gap-2 px-3 pt-3 min-w-0">
          <span className="text-xs text-ink-3">{t("nav.tapHint")}</span>
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
      {searchOpen && snapshot && <SearchModal snapshot={snapshot} onClose={() => setSearchOpen(false)} onSelect={(id) => { setSearchOpen(false); select(id); }} />}
    </GraphContext.Provider>
  );
}

function Arrow({ dir }: { dir: "left" | "right" }) {
  return <svg width="18" height="18" viewBox="0 0 20 20" style={{ transform: dir === "right" ? "scaleX(-1)" : undefined }}><path d="M11.89 2.68 5.02 9.55 4.59 10l.43.45 6.87 6.87.9-.9L6.37 10l6.42-6.43-.9-.9Z" fill="currentColor" /></svg>;
}
