"use client";
import { useEffect, useMemo, useRef, useState } from "react";
import MiniSearch from "minisearch";
import { useLocale, useTranslations } from "next-intl";
import type { GraphSnapshot } from "@/lib/types";
import { localized } from "@/lib/types";

type Doc = { id: string; ar: string; en: string; fr: string; aliases: string; people: string };

export function SearchModal({ snapshot, onClose, onSelect }: { snapshot: GraphSnapshot; onClose: () => void; onSelect: (id: string) => void }) {
  const t = useTranslations();
  const locale = useLocale();
  const [q, setQ] = useState("");
  const [active, setActive] = useState(0);
  const input = useRef<HTMLInputElement>(null);

  const index = useMemo(() => {
    const ms = new MiniSearch<Doc>({ fields: ["ar", "en", "fr", "aliases", "people"], storeFields: ["id"], searchOptions: { prefix: true, fuzzy: 0.2, boost: { ar: 2, en: 2, aliases: 1.5 } }, tokenize: (s) => s.split(/[\s،,\-–/()]+/).filter(Boolean) });
    ms.addAll(Object.values(snapshot.nodes).map((n) => ({
      id: n.id, ar: n.name.ar ?? "", en: n.name.en ?? "", fr: n.name.fr ?? "",
      aliases: [...(n.aliases?.ar ?? []), ...(n.aliases?.en ?? []), ...(n.aliases?.fr ?? [])].join(" "),
      people: n.people.map((p) => `${p.name?.ar ?? ""} ${p.name?.en ?? ""}`).join(" "),
    })));
    return ms;
  }, [snapshot]);
  const results = useMemo(() => (q.trim() ? index.search(q).slice(0, 10).map((r) => snapshot.nodes[r.id]).filter(Boolean) : []), [q, index, snapshot]);

  useEffect(() => { input.current?.focus(); }, []);
  useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") onClose();
      if (e.key === "ArrowDown") setActive((a) => Math.min(a + 1, results.length - 1));
      if (e.key === "ArrowUp") setActive((a) => Math.max(a - 1, 0));
      if (e.key === "Enter" && results[active]) onSelect(results[active].id);
    };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [results, active, onClose, onSelect]);

  return (
    <div className="fixed inset-0 z-50 flex items-start justify-center pt-[12vh] px-4" style={{ background: "rgba(0,0,0,0.45)" }} onClick={onClose}>
      <div className="card w-full max-w-xl shadow-2xl overflow-hidden" onClick={(e) => e.stopPropagation()}>
        <input ref={input} value={q} onChange={(e) => { setQ(e.target.value); setActive(0); }} placeholder={t("nav.search")} className="w-full bg-transparent px-5 py-4 text-base outline-none" />
        <div className="border-t border-line max-h-[50vh] overflow-y-auto">
          {q.trim() && results.length === 0 && <div className="px-5 py-4 text-sm text-ink-3">{t("nav.noResults")}</div>}
          {results.map((n, i) => (
            <button key={n.id} onMouseEnter={() => setActive(i)} onClick={() => onSelect(n.id)} className={`w-full text-start px-5 py-3 flex items-center gap-3 ${i === active ? "bg-hover" : ""}`}>
              <span className="inline-block w-2.5 h-2.5 rounded-full flex-none" style={{ background: n.sector ? `var(--c-${n.sector})` : "var(--brand)" }} />
              <span className="truncate">{localized(n.name, locale)}</span>
              <span className="ms-auto text-xs text-ink-3 truncate">{locale === "ar" ? n.name.en : n.name.ar}</span>
            </button>
          ))}
        </div>
      </div>
    </div>
  );
}
