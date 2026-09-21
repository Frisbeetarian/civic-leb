"use client";
import { useState } from "react";

/** Long descriptions collapse to a few lines on small screens, like CivLab's "Read more". */
export function ReadMore({ text, label }: { text: string; label: string }) {
  const [open, setOpen] = useState(false);
  const long = text.length > 260;
  return (
    <div className="mt-4">
      <p className={`text-[16px] leading-relaxed text-ink-2 ${long && !open ? "line-clamp-4 lg:line-clamp-none" : ""}`}>{text}</p>
      {long && !open && <button onClick={() => setOpen(true)} className="lg:hidden mt-1 text-sm text-ink-3 hover:text-ink">{label}</button>}
    </div>
  );
}
