export function Logomark({ size = 18 }: { size?: number }) {
  // Cedar-green seal: our own mark, same weight as CivLab's star.
  return (
    <svg width={size} height={size} viewBox="0 0 34 34" aria-hidden>
      <path fill="var(--brand)" d="M17 1l2.3 6.4 5.6-3.9-1.6 6.6 6.8-.8-4.9 4.7 6.2 2.9-6.6 1.7 4 5.6-6.7-1.5 1 6.8-5.3-4.4L17 33l-2.8-6.9-5.3 4.4 1-6.8-6.7 1.5 4-5.6-6.6-1.7 6.2-2.9-4.9-4.7 6.8.8-1.6-6.6 5.6 3.9Z" />
    </svg>
  );
}
