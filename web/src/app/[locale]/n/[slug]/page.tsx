import { notFound } from "next/navigation";
import { setRequestLocale } from "next-intl/server";
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
  return { title: `${localized(detail.node.name, locale)} · civicleb`, description: localized(detail.node.description, locale) || undefined };
}

export default async function NodePage({ params }: { params: Promise<{ locale: string; slug: string }> }) {
  const { locale, slug } = await params;
  setRequestLocale(locale);
  const detail = await fetchNode(slug);
  if (!detail) notFound();
  return <EntityCards detail={detail} />;
}
