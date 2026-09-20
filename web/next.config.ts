import type { NextConfig } from "next";
import createNextIntlPlugin from "next-intl/plugin";

const withNextIntl = createNextIntlPlugin("./src/i18n/request.ts");
const isStatic = process.env.STATIC_EXPORT === "1";

const nextConfig: NextConfig = {
  reactStrictMode: true,
  // STATIC_EXPORT=1 builds a fully static site into out/ from the bundled snapshot (no backend)
  ...(isStatic ? { output: "export", trailingSlash: true, images: { unoptimized: true } } : {}),
};

export default withNextIntl(nextConfig);
