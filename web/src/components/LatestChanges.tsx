"use client";
import { useMemo, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { Link } from "@/i18n/navigation";
import { useGraph } from "./Shell";
import { localized } from "@/lib/types";

/**
 * Until the sweep pipeline exists (decisions.md Q7), the changes feed is derived from the
 * published tenures in the snapshot: every current tenure that started inside the window.
 */
export function LatestChanges() {
  const t = useTranslations();
  const locale = useLocale();
  const snapshot = useGraph();
  const [days, setDays] = useState<7 | 30 | 90>(90);
  const today = useMemo(() => new Date(), []);

  const events = useMemo(() => {
    const out: { date: string; nodeId: string; positionName: string; person: string | null; status: string }[] = [];
    for (const n of Object.values(snapshot.nodes)) {
      for (const p of n.people) {
        if (!p.startedAt) continue;
        out.push({ date: p.startedAt, nodeId: n.type === "dept_head" || n.type === "seat" ? n.id : p.positionId, positionName: localized(p.positionName, locale), person: p.name ? localized(p.name, locale) : null, status: p.status });
      }
    }
    const seen = new Set<string>();
    return out.filter((e) => { const k = `${e.nodeId}|${e.date}|${e.person ?? ""}`; if (seen.has(k)) return false; seen.add(k); return true; }).sort((a, b) => b.date.localeCompare(a.date));
  }, [snapshot, locale]);

  const since = new Date(today); since.setDate(since.getDate() - days);
  const inWindow = events.filter((e) => new Date(e.date) >= since);
  const last = events[0];
  const lastAgo = last ? Math.max(0, Math.round((today.getTime() - new Date(last.date).getTime()) / 86400000)) : null;
  const fmt = (d: string) => new Intl.DateTimeFormat(locale === "ar" ? "ar-LB" : "en-GB", { month: "short", day: "numeric" }).format(new Date(d));

  return (
    <section className="card p-6">
      <div className="flex items-center justify-between">
        <h2 className="text-[20px] font-semibold">{t("changes.title")}</h2>
        <div className="flex rounded-lg bg-card-2 p-0.5 text-[11px] font-mono">
          {([7, 30, 90] as const).map((d) => <button key={d} onClick={() => setDays(d)} className={`px-2 py-1 rounded-md ${days === d ? "bg-card text-ink" : "text-ink-3"}`}>{d}D</button>)}
        </div>
      </div>
      <p className="mt-2 text-[16px] text-ink-2">{t("changes.intro")}</p>
      <div className="mt-4 grid grid-cols-3 gap-2 min-w-0">
        <Stat label={t("changes.vacant")} value={snapshot.counts.vacantSeats} note={t("changes.vacantNote")} />
        <Stat label={t("changes.acting")} value={snapshot.counts.actingOfficials} note={t("changes.actingNote")} />
        <Stat label={t("changes.last")} value={lastAgo === null ? "—" : `${lastAgo}d`} note={last ? t("changes.lastNote", { date: fmt(last.date) }) : ""} />
      </div>
      <div className="mt-5 flex items-center justify-between"><span className="mono-label">{t("changes.timeline")}</span><span className="text-xs text-ink-3">{t("changes.inWindow", { count: inWindow.length })}</span></div>
      <ul className="mt-3 space-y-2">
        {inWindow.slice(0, 6).map((e, i) => (
          <li key={i} className="rounded-xl bg-card-2 p-3">
            <div className="flex items-center justify-between text-[11px] font-mono">
              <span className="px-1.5 py-0.5 rounded" style={{ color: `var(--c-${e.person ? "legislative" : "executive"})`, background: "color-mix(in srgb, currentColor 15%, transparent)" }}>{t(`changes.kind.${e.person ? "appointed" : "departure"}`)}</span>
              <span className="text-ink-3">{fmt(e.date)}</span>
            </div>
            <Link href={`/n/${e.nodeId}`} className="block mt-1.5 text-[16px] font-medium hover:underline">{e.positionName}</Link>
            <div className="mt-1 text-sm text-ink-2"><span className="mono-label me-2">{t("changes.in")}</span>{e.person ?? t("tenure.vacant")}<span className="text-ink-3"> · {t.has(`tenure.${e.status}`) ? t(`tenure.${e.status}`) : e.status}</span></div>
          </li>
        ))}
        {inWindow.length === 0 && <li className="text-sm text-ink-3">{t("changes.none")}</li>}
      </ul>
    </section>
  );
}

function Stat({ label, value, note }: { label: string; value: number | string; note: string }) {
  return (
    <div className="rounded-xl bg-card-2 p-2.5 sm:p-3 min-w-0">
      <div className="mono-label truncate">{label}</div>
      <div className="text-xl sm:text-2xl font-semibold tabular-nums mt-1">{value}</div>
      <div className="text-[10px] font-mono text-ink-3 mt-0.5 truncate">{note}</div>
    </div>
  );
}
