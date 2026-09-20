"use client";
import { useEffect, useState, useSyncExternalStore } from "react";

export function ThemeToggle() {
  const [dark, setDark] = useState<boolean>(() => {
    if (typeof window === "undefined") return true;
    try {
      const stored = localStorage.getItem("civicleb-theme");
      return stored ? stored === "dark" : true;
    } catch { return true; }
  });
  const mounted = useSyncExternalStore(() => () => {}, () => true, () => false);
  useEffect(() => { document.documentElement.dataset.theme = dark ? "dark" : "light"; }, [dark]);
  const toggle = () => { const d = !dark; setDark(d); try { localStorage.setItem("civicleb-theme", d ? "dark" : "light"); } catch {} };
  return (
    <button onClick={toggle} className="card w-10 h-10 flex items-center justify-center hover:bg-hover" aria-label="theme">
      {mounted ? (dark ? <Sun /> : <Moon />) : null}
    </button>
  );
}
const Sun = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8"><circle cx="12" cy="12" r="4" /><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" /></svg>;
const Moon = () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z" /></svg>;
