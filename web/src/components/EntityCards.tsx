import { getLocale, getTranslations } from "next-intl/server";
import type { NodeDetail, Localized } from "@/lib/types";
import { localized } from "@/lib/types";
import { Link } from "@/i18n/navigation";

export async function EntityCards({ detail }: { detail: NodeDetail }) {
  const t = await getTranslations();
  const locale = await getLocale();
  const { node, edges, connected } = detail;
  const byId = Object.fromEntries(connected.map((c) => [c.id, c])) as Record<string, { id: string; name: Localized; sector: string | null }>;
  const name = (id: string) => localized(byId[id]?.name, locale) || id;
  const color = node.sector ? `var(--c-${node.sector})` : "var(--c-constituency)";
  const fmtYear = (d: string | null) => (d ? new Date(d).getFullYear() : "");
  const chip = (id: string) => (byId[id] ? <NodeChip href={`/n/${id}`} label={name(id)} color={byId[id].sector ? `var(--c-${byId[id].sector})` : color} /> : null);
  const headPeople = node.type === "dept_head" ? node.people : node.people.filter((p) => p.positionId === node.head);
  const otherPeople = node.type === "dept_head" ? [] : node.people.filter((p) => p.positionId !== node.head);

  return (
    <>
      <section className="card p-6">
        <h1 className="text-[32px] font-semibold leading-tight tracking-tight">{localized(node.name, locale)}</h1>
        <div className="text-ink-3 mt-1">{locale === "ar" ? node.name.en : node.name.ar}</div>
        {node.status && node.status !== "active" && (
          <div className="mt-3 text-sm"><span className="font-medium" style={{ color }}>{t.has(`status.${node.status}`) ? t(`status.${node.status}`) : node.status}</span>{node.statusNote && <span className="text-ink-2"> — {node.statusNote}</span>}</div>
        )}
        {localized(node.description, locale) && <p className="mt-4 text-[16px] leading-relaxed text-ink-2">{localized(node.description, locale)}</p>}
        <div className="mt-4 flex flex-wrap gap-4 text-[16px]">
          {node.legalSource?.url && <a className="link" href={node.legalSource.url} target="_blank" rel="noreferrer">{t("panel.legalSource")}{!node.legalSource.inForce && <span className="text-ink-3"> ({t("panel.notInForce")})</span>}</a>}
          {node.officialUrl && <a className="link" href={node.officialUrl} target="_blank" rel="noreferrer">{t("panel.officialSite")}</a>}
        </div>
        {(node.confession || node.grade) && (
          <dl className="mt-4 grid grid-cols-[auto_1fr] gap-x-4 gap-y-1 text-sm">
            {node.confession && (<><dt className="text-ink-3">{t("confession.label")}</dt><dd>{t(`confession.${node.confession}`)}{node.confessionBasis && <span className="text-ink-3"> · {t(`confession.basis.${node.confessionBasis}`)}</span>}{node.confessionSourceUrl && <a className="ms-1 link" href={node.confessionSourceUrl} target="_blank" rel="noreferrer">↗</a>}</dd></>)}
            {node.grade && (<><dt className="text-ink-3">{t("panel.grade")}</dt><dd>{node.grade === "one" ? "I" : "≈ I"}</dd></>)}
          </dl>
        )}
        {(headPeople.length > 0) && (
          <div className="mt-5">
            {node.type !== "dept_head" && node.head && <div className="text-[16px] text-ink-2 mb-2">{name(node.head)}</div>}
            <ul className="space-y-2">
              {headPeople.map((p, i) => <PersonCard key={i} name={p.name ? localized(p.name, locale) : t("tenure.vacant")} sub={`${t.has(`tenure.${p.status}`) ? t(`tenure.${p.status}`) : p.status}${p.startedAt ? ` ${fmtYear(p.startedAt)}` : ""}${p.party ? ` · ${p.party}` : ""}`} img={p.imageUrl} />)}
            </ul>
          </div>
        )}
        {node.seatsCount ? <div className="mt-4 text-[16px] text-ink-2">{t("panel.seats", { count: node.seatsCount })}</div> : null}
      </section>

      {node.parent && byId[node.parent] && (
        <section className="card p-6"><h2 className="text-[20px] font-semibold mb-3">{t("panel.parent")}</h2>{chip(node.parent)}</section>
      )}
      {node.headOf && byId[node.headOf] && (
        <section className="card p-6"><h2 className="text-[20px] font-semibold mb-3">{t("panel.headOf")}</h2>{chip(node.headOf)}</section>
      )}
      {otherPeople.length > 0 && (
        <section className="card p-6">
          <div className="flex items-center justify-between mb-3"><h2 className="text-[20px] font-semibold">{t("panel.people")}</h2><span className="text-ink-3 text-[16px]">{otherPeople.length}</span></div>
          <ul className="grid grid-cols-1 sm:grid-cols-2 gap-2">
            {otherPeople.map((p, i) => <PersonCard key={i} name={p.name ? localized(p.name, locale) : t("tenure.vacant")} sub={localized(p.positionName, locale)} img={p.imageUrl} href={byId[p.positionId] ? `/n/${p.positionId}` : undefined} />)}
          </ul>
        </section>
      )}
      {node.children && node.children.length > 0 && (
        <section className="card p-6"><h2 className="text-[20px] font-semibold mb-3">{t("panel.children")}</h2><div className="flex flex-wrap gap-2">{node.children.map((c) => <span key={c}>{chip(c)}</span>)}</div></section>
      )}
      {edges.some((e) => byId[e.fromId === node.id ? e.toId : e.fromId]) && <section className="card p-6">
        <h2 className="text-[20px] font-semibold mb-3">{t("panel.connected")}</h2>
        <ul className="divide-y divide-line">
          {edges.map((e) => {
            const outgoing = e.fromId === node.id;
            const other = outgoing ? e.toId : e.fromId;
            if (!byId[other]) return null;
            const verb = t.has(`edges.${e.type}`) ? t(`edges.${e.type}`) : e.type;
            return (
              <li key={e.id} className="py-2.5 text-[16px] flex flex-wrap items-baseline gap-x-1.5">
                {outgoing ? <><span className="text-ink-3">{verb}</span><Link className="font-medium hover:underline" href={`/n/${other}`}>{name(other)}</Link></> : <><Link className="font-medium hover:underline" href={`/n/${other}`}>{name(other)}</Link><span className="text-ink-3">{verb}</span></>}
                {e.seatsAppointed > 1 && <span className="text-ink-3 text-sm">· {t("panel.seats", { count: e.seatsAppointed })}</span>}
                {typeof e.metadata?.cite === "string" && <span className="text-ink-3 text-sm">· {e.metadata.cite as string}</span>}
              </li>
            );
          })}
        </ul>
      </section>}
    </>
  );
}

function PersonCard({ name, sub, img, href }: { name: string; sub: string; img: string | null; href?: string }) {
  const inner = (
    <>
      <div className="w-12 h-12 rounded-lg bg-card overflow-hidden flex-none border border-line">
        {/* eslint-disable-next-line @next/next/no-img-element */}
        {img && <img src={img} alt="" className="w-full h-full object-cover" />}
      </div>
      <div className="min-w-0"><div className="font-medium truncate">{name}</div><div className="text-sm text-ink-3 truncate">{sub}</div></div>
    </>
  );
  const cls = "flex items-center gap-3 rounded-xl bg-card-2 border border-line p-2.5 text-[16px]";
  return href ? <li><Link href={href} className={`${cls} hover:bg-hover`}>{inner}</Link></li> : <li className={cls}>{inner}</li>;
}

function NodeChip({ href, label, color }: { href: string; label: string; color: string }) {
  return <Link href={href} className="chip text-[16px] hover:bg-hover"><span className="w-2.5 h-2.5 rounded-full" style={{ background: color }} />{label}</Link>;
}
