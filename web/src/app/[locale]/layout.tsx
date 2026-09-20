import type { Metadata } from "next";
import { Inter, IBM_Plex_Sans_Arabic } from "next/font/google";
import { NextIntlClientProvider, hasLocale } from "next-intl";
import { getMessages, getTranslations, setRequestLocale } from "next-intl/server";
import { notFound } from "next/navigation";
import { Suspense } from "react";
import { routing, isRtl } from "@/i18n/routing";
import { fetchGraph } from "@/lib/graph";
import { Shell } from "@/components/Shell";
import "../globals.css";

const inter = Inter({ subsets: ["latin"], variable: "--font-inter", display: "swap" });
const plexArabic = IBM_Plex_Sans_Arabic({ subsets: ["arabic"], weight: ["400", "500", "600", "700"], variable: "--font-plex-arabic", display: "swap" });

export function generateStaticParams() {
  return routing.locales.map((locale) => ({ locale }));
}

const SITE = "https://civ-leb.com";

export async function generateMetadata({ params }: { params: Promise<{ locale: string }> }): Promise<Metadata> {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "site" });
  const title = `${t("name")} · ${t("govLabel")}`;
  const description = t("metaDescription");
  return {
    metadataBase: new URL(SITE),
    title,
    description,
    alternates: { canonical: `/${locale}/`, languages: { ar: "/ar/", en: "/en/" } },
    openGraph: { type: "website", siteName: "Civic Leb", title, description, url: `/${locale}/`, locale: locale === "ar" ? "ar_LB" : "en_US", images: [{ url: "/og.png", width: 1200, height: 630, alt: t("tagline") }] },
    twitter: { card: "summary_large_image", title, description, images: ["/og.png"] },
  };
}

export default async function LocaleLayout({ children, params }: { children: React.ReactNode; params: Promise<{ locale: string }> }) {
  const { locale } = await params;
  if (!hasLocale(routing.locales, locale)) notFound();
  setRequestLocale(locale);
  const [messages, snapshot] = await Promise.all([getMessages(), fetchGraph()]);

  return (
    <html lang={locale} dir={isRtl(locale) ? "rtl" : "ltr"} className={`${inter.variable} ${plexArabic.variable}`} data-theme="dark" suppressHydrationWarning>
      <body className="min-h-full">
        <NextIntlClientProvider messages={messages}>
          <Suspense fallback={null}>
            <Shell snapshot={snapshot}>{children}</Shell>
          </Suspense>
        </NextIntlClientProvider>
      </body>
    </html>
  );
}
