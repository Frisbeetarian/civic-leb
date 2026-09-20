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
  const title = `${name} · civicleb`;
  const description = localized(detail.node.description, locale) || t("metaDescription");
  return {
    title,
    description,
    alternates: { canonical: `/${locale}/n/${slug}/`, languages: { ar: `/ar/n/${slug}/`, en: `/en/n/${slug}/` } },
    openGraph: { type: "article", siteName: "civicleb", title, description, url: `/${locale}/n/${slug}/`, images: [{ url: "/og.png", width: 1200, height: 630, alt: name }] },
    twitter: { card: "summary_large_image", title, description, images: ["/og.png"] },
  };
}

export default async function NodePage({ params }: { params: Promise<{ locale: string; slug: string }> }) {
  const { locale, slug } = await params;
  setRequestLocale(locale);
  const detail = await fetchNode(slug);
  if (!detail) notFound();
  return <EntityCards detail={detail} />;
}
