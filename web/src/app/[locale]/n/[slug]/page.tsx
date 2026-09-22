import { notFound } from "next/navigation";
import { getTranslations, setRequestLocale } from "next-intl/server";
import type { Metadata } from "next";
import { fetchGraph, fetchNode } from "@/lib/graph";
import { routing } from "@/i18n/routing";
import { localized } from "@/lib/types";
import { EntityCards } from "@/components/EntityCards";

export const dynamicParams = false;

export async function generateStaticParams() {
  const snapshot = await fetchGraph();
  return routing.locales.flatMap((locale) => Object.keys(snapshot.nodes).map((slug) => ({ locale, slug })));
}

export async function generateMetadata({ params }: { params: Promise<{ locale: string; slug: string }> }): Promise<Metadata> {
  const { locale, slug } = await params;
  const detail = await fetchNode(slug);
  if (!detail) return {};
  const t = await getTranslations({ locale, namespace: "site" });
  const name = localized(detail.node.name, locale);
  const title = `${name} · Civic Leb`;
  const description = localized(detail.node.description, locale) || t("metaDescription");
  const snapshot = await fetchGraph();
  return {
    title,
    description,
    authors: [{ name: "Muhammad Sulayman Haydar", url: "https://github.com/Frisbeetarian" }],
    other: { "article:published_time": "2026-09-20", "article:modified_time": snapshot.generatedAt, "last-modified": snapshot.generatedAt },
    alternates: { canonical: `/${locale}/n/${slug}/`, languages: { ar: `/ar/n/${slug}/`, en: `/en/n/${slug}/` } },
    openGraph: { type: "article", siteName: "Civic Leb", title, description, url: `/${locale}/n/${slug}/`, publishedTime: "2026-09-20", modifiedTime: snapshot.generatedAt, authors: ["https://github.com/Frisbeetarian"], images: [{ url: "/og.png", width: 1200, height: 630, alt: name }] },
    twitter: { card: "summary_large_image", title, description, images: ["/og.png"] },
  };
}

export default async function NodePage({ params }: { params: Promise<{ locale: string; slug: string }> }) {
  const { locale, slug } = await params;
  setRequestLocale(locale);
  const detail = await fetchNode(slug);
  if (!detail) notFound();
  const snapshot = await fetchGraph();
  const name = localized(detail.node.name, locale);
  const jsonLd = {
    "@context": "https://schema.org",
    "@type": "Article",
    headline: name,
    description: localized(detail.node.description, locale) || undefined,
    inLanguage: locale,
    url: `https://civ-leb.com/${locale}/n/${slug}/`,
    datePublished: "2026-09-20",
    dateModified: snapshot.generatedAt,
    author: { "@type": "Person", name: "Muhammad Sulayman Haydar", url: "https://github.com/Frisbeetarian" },
    publisher: { "@type": "Organization", name: "Civic Leb", url: "https://civ-leb.com", logo: { "@type": "ImageObject", url: "https://civ-leb.com/og.png" } },
    image: "https://civ-leb.com/og.png",
    isPartOf: { "@id": "https://civ-leb.com/#site" },
    license: "https://creativecommons.org/licenses/by/4.0/",
    about: detail.node.legalSource?.url ? { "@type": "Legislation", name: localized(detail.node.legalSource.title, locale), url: detail.node.legalSource.url } : undefined,
  };
  return (
    <>
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }} />
      <EntityCards detail={detail} updatedAt={snapshot.generatedAt} districtOrder={snapshot.layout.pills.find((p) => p.memberNodeType === "seat")?.groupOrder} />
    </>
  );
}
