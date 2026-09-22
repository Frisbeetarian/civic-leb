"use client";
import { useState } from "react";
import { useTranslations } from "next-intl";
import { familyColor, familyOrder, type EdgeFamily } from "@/lib/palette";
import { Glyph } from "./Glyph";

const kinds = ["elected", "seat", "department", "dept_head", "commission", "advisory", "court", "confessional_court", "security_service", "state_company", "regulator", "oversight"] as const;

export function Legend({ hidden, onToggle, onReset, showAllEdges, onToggleEdges, forceOpen = false, onClose }: { hidden: Set<string>; onToggle: (kind: string) => void; onReset: () => void; showAllEdges: boolean; onToggleEdges: () => void; forceOpen?: boolean; onClose?: () => void }) {
  const t = useTranslations();
  const [openState, setOpen] = useState(false);
  const open = forceOpen || openState;
  const label = (k: string) => (t.has(`types.${k}`) ? t(`types.${k}`) : t.has(`subtypes.${k}`) ? t(`subtypes.${k}`) : k);
  return (
    <div className="relative">
      {!forceOpen && <button onClick={() => setOpen((o) => !o)} className="card h-9 ps-3 pe-2.5 flex items-center gap-2 text-sm hover:bg-hover" aria-expanded={open}>
        <span>{t("nav.legend")}</span>
        {!open && hidden.size > 0 && <span className="text-ink-3 text-xs">{t("nav.hidden", { count: hidden.size })}</span>}
        <svg width="14" height="8" viewBox="0 0 18 10" style={{ transform: open ? "rotate(180deg)" : undefined, transition: "transform 140ms" }}><path d="M1.64.74.74 1.64 8.55 9.45 9 9.88l.45-.43 7.81-7.81-.9-.9L9 8.1 1.64.74Z" fill="currentColor" /></svg>
      </button>}
      {open && (
        <div className={`card p-2 shadow-xl text-xs space-y-3 overflow-y-auto ${forceOpen ? "w-full max-h-[60vh]" : "absolute bottom-11 start-0 w-64 max-h-[70vh]"}`}>
          {forceOpen && <div className="flex items-center justify-between px-2 pt-1"><span className="font-medium text-sm">{t("nav.legend")}</span><button onClick={onClose} className="text-ink-3 px-1" aria-label="close">✕</button></div>}
          <section>
            <div className="mono-label px-2 py-1">{t("nav.entities")}</div>
            {kinds.map((k) => (
              <button key={k} onClick={() => onToggle(k)} className={`w-full flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-hover text-start ${hidden.has(k) ? "opacity-40" : ""}`}>
                <svg width="18" height="18" viewBox="-11 -11 22 22"><Glyph kind={k} r={8} color="var(--ink-2)" fillAlpha={0.35} /></svg>
                <span className={hidden.has(k) ? "line-through" : ""}>{label(k)}</span>
              </button>
            ))}
          </section>
          <section>
            <div className="mono-label px-2 py-1">{t("nav.relationships")}</div>
            {familyOrder.map((f) => (
              <div key={f} className="flex items-center gap-2 px-2 py-1.5"><svg width="28" height="10" viewBox="0 0 28 10"><EdgeSwatch family={f} /></svg><span>{t(`edgeFamilies.${f}`)}</span></div>
            ))}
            <label className="flex items-center gap-2 px-2 py-1.5 cursor-pointer"><input type="checkbox" checked={showAllEdges} onChange={onToggleEdges} /><span>{t("nav.showAllEdges")}</span></label>
          </section>
          {hidden.size > 0 && <button onClick={onReset} className="mono-label w-full text-start px-2 py-1.5 rounded-lg hover:bg-hover">{t("nav.showAll")}</button>}
        </div>
      )}
    </div>
  );
}

function EdgeSwatch({ family }: { family: EdgeFamily }) {
  const c = familyColor[family];
  const dash = family === "hierarchy" ? "3 2" : family === "tutelage" ? "5 2" : undefined;
  return (
    <g stroke={c} fill="none" strokeWidth={1.5} strokeDasharray={dash}>
      <line x1={1} y1={5} x2={20} y2={5} />
      {family === "election" && <path d="M18,2 L22,5 L18,8 M22,2 L26,5 L22,8" fill={c} stroke="none" />}
      {(family === "appointment" || family === "command") && <path d="M20,2 L26,5 L20,8 Z" fill={c} stroke="none" />}
      {(family === "oversight" || family === "judicial" || family === "tutelage") && <path d="M20,2 L25,5 L20,8" />}
    </g>
  );
}
