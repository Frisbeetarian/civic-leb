# 04 — Current state of the Lebanese state (as of 2026-09-18), appointments since January 2025, and data sources

Research note for civicleb. Written 2026-09-18. Every claim is date-stamped and cited; anything not
verified against a fetched source is marked **UNVERIFIED**. No names are guessed.

Method: official sites probed with `curl` (pcm.gov.lb, lp.gov.lb, presidency.gov.lb, nna-leb.gov.lb, legallaw.ul.edu.lb and
the civil-society sites in §5); Wikipedia (en) wikitext via `action=raw`; Wikidata via SPARQL and `wbsearchentities`;
press coverage through Google News RSS search (`https://news.google.com/rss/search?q=…&hl=ar&gl=LB&ceid=LB:ar`, and the
`en-US` variant) used as a search substitute because WebSearch was unavailable. Headlines are cited with outlet and
date; where the article body was not opened the row says so. Cross-references: `01-constitutional-framework.md` §5
(Parliament) and `02-executive-inventory.md` §0 (the 24-member cabinet) are not repeated here.

**Warning on press dates (see §5.4).** Google News assigns fake 2026 dates to old stories re-indexed by some outlets
(mtv.com.lb English, arabnews.com, en.kataeb.org). Four such false positives were caught during this pass — a "foreign
minister quits" headline (Reuters, 2 Aug 2020), "Judge Makkieh appointed Cabinet Secretary General" (2020), "Defense
Minister appoints acting army commander" (Jan 2025) and "Parliament postpones municipal elections for a second time"
(2023). Every 2026 item below was therefore corroborated with at least one Arabic-language headline from a second
outlet, or is marked UNVERIFIED.

Status of this file: sections 1–6 complete (2026-09-18).

---

## 1. Top of the state as of 2026-09-18

| Office | Holder | Since | Instrument / event | Predecessor | Sources | Status |
|---|---|---|---|---|---|---|
| President of the Republic | Joseph Aoun (جوزاف عون, Wikidata Q29033962) | 2025-01-09 | Elected by Parliament, 2nd round of the 9 Jan 2025 session (13th session of the 2022–2025 vacancy) | Michel Aoun (office vacant 2022-10-31 → 2025-01-09) | https://en.wikipedia.org/wiki/2025_in_Lebanon ; Wikidata P39 start 2025-01-09 (cached SPARQL, 2026-09-18); https://www.presidency.gov.lb/presidency/3 (biography, oath, election minutes) | VERIFIED |
| Prime Minister | Nawaf Salam (نواف سلام, Q638463) | Designated 2025-01-13 (84/128 MPs in binding consultations); government formed 2025-02-08; confidence 2025-02-26 (95/128); **confidence renewed 2026-09-16 (68 for / 12 against / 2 abstentions)** | Decree 52 of 8-2-2025 (designation) and Decree 53 of 8-2-2025 (formation) — https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=13587 (HTTP 200 on 2026-09-18); vote: https://www.lp.gov.lb/ContentRecordDetails?Id=36998 (fetched 2026-09-18) | Najib Mikati (caretaker since 2022-05) | https://en.wikipedia.org/wiki/Cabinet_of_Nawaf_Salam ; Wikidata P39 start 2025-02-08 | VERIFIED |
| Speaker of Parliament | Nabih Berri (نبيه بري, Q704220) | 1992-10-20 (first election); most recently re-elected 2022-05-31 (65 votes) | Parliament vote at first session of the 2022 legislature | — (continuous since 1992) | https://en.wikipedia.org/wiki/Nabih_Berri ; https://www.lp.gov.lb/ContentRecordDetails?Id=36998 (presided the 15–16 Sep 2026 sittings) | VERIFIED |
| Deputy Speaker | Elias Bou Saab (الياس بو صعب, Q21005151) | 2022-05-31 | Parliament vote | Elie Ferzli | https://en.wikipedia.org/wiki/Deputy_Speaker_of_the_Parliament_of_Lebanon ; still acting as Deputy Speaker in press of 19 May, 13–14 Jul 2026 (Google News RSS, query GN-A below: MTV Lebanon 19 May 2026 "بو صعب بعد هيئة مكتب المجلس"; Al-Binaa 14 Jul 2026) | VERIFIED |
| Deputy Prime Minister | Tarek Mitri (طارق متري, Q3515658) | 2025-02-08 | Decree 53 of 8-2-2025 | Saadeh Al Shami | Wikidata P39 (start 2025-02-08, replaces Saadeh Al Shami); pcm ministers page | VERIFIED |
| Secretary General of the Council of Ministers (الأمين العام لمجلس الوزراء) | Judge Mahmoud Makkieh (القاضي محمود مكية) | In post before January 2025; appointment decree and date **UNVERIFIED** this session (the mtv.com.lb English item "Judge Makkieh appointed Cabinet Secretary General" carries a false Google date of 16 Aug 2026 and is a re-indexed 2020 story — see §5.4) | — | Predecessor UNVERIFIED | https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=13090 (Directorate General of the Presidency of the Council of Ministers, live 2026-09-18, cited in part 02 §0) | HOLDER VERIFIED; DATES UNVERIFIED |
| "Secretary General of the Presidency" — the post is legally the **Director General of the Presidency of the Republic** (مدير عام رئاسة الجمهورية); the Presidency's apparatus is the "المديرية العامة لرئاسة الجمهورية" (Legislative Decree 160 of 12/6/1959; Decree 2041 of 27/8/1959) and its first branch is the "فرع الأمانة العامة" headed by a director general/branch head | Dr Antoine Choucair (أنطوان شقير) — listed "من 22/9/2011 لغاية تاريخه" (from 22/9/2011 to date) | 2011-09-22 | Decree 5919 of 22/9/2011 ("تعيينه مديراً عاماً لرئاسة الجمهورية"); earlier Decree 39 of 16/8/2008 seconded him from IDAL to the Presidency. (The organisation page dates Decree 5919 "22/7/2011"; the directors page says 22/9/2011 — discrepancy on the official site.) | Naji Abi Assi (10/9/2008–16/4/2011, Decree 197 of 10/9/2008) | https://www.presidency.gov.lb/former-directors (fetched 2026-09-18) ; https://www.presidency.gov.lb/gdpr (organisation, Decision 20) | VERIFIED (official page, "to date" as of 2026-09-18) |
| Secretary General of Parliament (الأمين العام لمجلس النواب) | Adnan Daher (عدنان ضاهر) | UNVERIFIED (in post by 2016) | UNVERIFIED (Parliament staff appointment) | UNVERIFIED | KUNA 8 Feb 2016 "الامين العام لمجلس النواب اللبناني الدكتور عدنان ضاهر"; Elnashra 30 Nov 2021 (MP resignation "قدم استقالته خطيا الى امين عام مجلس النواب"); An-Nahar 25 Nov 2025 "المعجم النيابي اللبناني لـضاهر وغنام" (all via Google News RSS query GN-B). lp.gov.lb has no page naming the Secretary General; the Secretariat General is referenced only as publisher of the monthly "النشرة البرلمانية" — https://www.lp.gov.lb/CustomPage.aspx?Id=11 (cached) | HOLDER PLAUSIBLE, **UNVERIFIED for 2026** (no primary page; no reported change found) |

Notes:
- The 9 January 2025 presidential election ended a vacancy of 2 years and 2 months (since Michel Aoun's term ended 31 October 2022). Source: https://en.wikipedia.org/wiki/2025_in_Lebanon (fetched 2026-09-18).
- The Presidency site is the only official site found that publishes a **structured tenure list with decree numbers and dates** (holder, from/to, decree number, decree date, employment status) — https://www.presidency.gov.lb/former-directors. It is the model for the civicleb tenure record (see §6).
- Google News query URLs referenced as GN-x are listed in §4.4.

### 1.1 Ministers

The 24 members of the Salam cabinet are inventoried in `02-executive-inventory.md` §0 (pcm.gov.lb pageid=13587, fetched 2026-09-18). No change since 8 February 2025 — see §2.

---

## 2. Government status as of 2026-09-18

| Question | Answer (2026-09-18) | Evidence |
|---|---|---|
| Which government? | 78th government, Nawaf Salam, formed by Decree 53 of 8-2-2025 (designation Decree 52 of 8-2-2025); confidence 26 Feb 2025 with 95 votes | https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=13587 (HTTP 200, 2026-09-18); Naharnet 27 Feb 2025 "Govt wins confidence vote with support of 95 MPs" (GN-C) |
| Still in office, caretaker, or replaced? | **In office with full powers.** Not caretaker: no resignation, no new election (Parliament extended to 31/5/2028, see §3), and the government **obtained a renewed vote of confidence on 16 September 2026**: 4th sitting of the 2nd extraordinary session, 15–16 Sep 2026, general-policy debate under Rules Arts. 136–137, 59 MPs spoke, MP Gebran Bassil requested the vote; result 68 confidence / 12 no confidence / 2 abstentions, roll-call | https://www.lp.gov.lb/ContentRecordDetails?Id=36998 (title: "الحكومة تنال الثقة بأكثرية 68 نائباً مقابل 12 لا ثقة وامتناع نائبين عن التصويت", fetched 2026-09-18) |
| Ministerial changes since Feb 2025? | **None found.** All 24 members listed on pcm.gov.lb on 2026-09-18 are the 8 Feb 2025 appointees; en.wikipedia "Cabinet of Nawaf Salam" (last edited 28 Jun 2026) records no resignation or reshuffle; "2026 in Lebanon" (fetched 2026-09-18) records none. The only ministerial-level departure reported in 2025–2026 press concerned pressure on Foreign Minister Youssef Raggi (Hezbollah MP Ibrahim Moussawi, 28 Jul 2026: "ليس هناك وزير خارجية للبنان بل مندوب ميليشياوي"; MTV 4 Sep 2026: "مسألة رجّي على طريق الحل البطيء") — **no resignation** as of 2026-09-18; Raggi received the new French and Nigerian ambassadors' credentials on 2 Sep 2026 (Ad-Diyar) | pcm.gov.lb pageid=13587; https://en.wikipedia.org/wiki/Cabinet_of_Nawaf_Salam ; Google News RSS GN-D (Arabic query on Raggi) |
| False positive to ignore | "Lebanese foreign minister quits over slow reforms, Aoun adviser takes over" dated 15 Sep 2026 under Arab News is the Reuters headline of 2 Aug 2020 (Nassif Hitti resignation; Charbel Wehbe took over) re-indexed with a new date | Google News RSS GN-E shows the identical headline under Reuters, 02 Aug 2020 |
| Cabinet activity markers 2026 | 5 Mar 2026: ban on IRGC activity, visas for Iranians; 24 Mar 2026: Iranian ambassador Mohammad Reza Raouf Sheibani declared persona non grata; 22 Sep 2025: 2026 budget approved; 17 Sep 2026: cabinet approved six additional public-sector salaries in the 2027 budget and a batch of appointments (§4) | https://en.wikipedia.org/wiki/Cabinet_of_Nawaf_Salam ; An-Nahar 17 Sep 2026 "ستة رواتب إضافية للقطاع العام في 2027 - قرارات مجلس الوزراء" (GN-F) |

Date-stamp: all rows checked 2026-09-18.

---

## 3. Parliament as of 2026-09-18

Full treatment (districts, Bureau, committees, extension law, Constitutional Council decision, indicative blocs) is in
`01-constitutional-framework.md` §5. This section only records the current-state facts needed for the graph.

| Item | State on 2026-09-18 | Source |
|---|---|---|
| May 2026 election | **Not held.** Decree 2438 (2 Feb 2026) had called the electorate for 10 May 2026; after the March 2026 war Parliament passed Law 41 of 9/3/2026 (76 for / 41 against / 4 abstentions) extending its own term to **31 May 2028**; Constitutional Council Decision 7/2026 of 7 Apr 2026 rejected the three challenges | part 01 §5.4 (lp.gov.lb Id=35713; cc.gov.lb Decision 7/2026 PDF; https://en.wikipedia.org/wiki/2028_Lebanese_general_election) |
| Sitting legislature | The 2022 Parliament (elected 15 May 2022). Speaker Berri, Deputy Speaker Bou Saab, Bureau of 31 May 2022; committee chairs elected 21 Oct 2025 (part 01 §5.3) | lp.gov.lb (part 01) |
| Seats filled | **127 of 128.** One vacancy: Ghassan Skaff (غسان سكاف, Greek Orthodox seat, West Bekaa–Rashaya, independent, list "Independent National Decision") died 13 December 2025 | https://en.wikipedia.org/wiki/Ghassan_Skaff ("In office 17 May 2022 – 13 December 2025"); https://en.wikipedia.org/wiki/2022_Lebanese_general_election (member table, cached) |
| By-election for the Skaff seat | No by-election reported (Google News RSS query "انتخابات فرعية البقاع الغربية راشيا مقعد سكاف after:2025-12-13" returned no items on 2026-09-18). Whether the seat is filled before 2028 is **UNVERIFIED**; the by-election rule of Law 44/2017 (article number UNVERIFIED) is the legal hook to model | GN-G |
| Composition / blocs | As in part 01 §5.4 (indicative bloc sizes; exact September 2026 sizes UNVERIFIED because lp.gov.lb's blocs page redirects — `lp_blocs.html` cached response is "Document Moved") | part 01 |
| 2026 legislative output (for the changes feed) | 9 Mar 2026 extension law; 15 Jul 2026 legislative session ("كامل نتائج جلسة 15/7/2026 التشريعية", Legal Agenda); July 2026 law extending the Lebanese University president's term (MTV 16 Jul 2026), **annulled by the Constitutional Council on 3 Sep 2026** (almodon 3 Sep 2026 "الدستوري أبطل تمديد ولاية رئيس الجامعة"; Legal Agenda 8 Sep 2026); general amnesty law (Naharnet 12 Aug 2026 "Lebanon's sweeping amnesty law: What we know"; Enab Baladi 12 Aug 2026); abolition of the death penalty (SCMP 11 Aug 2026 "Lebanon becomes first Arab nation to abolish death penalty"; Bou Saab statements 13 Jul 2026, Elnashra); Bank Restructuring Law approved (Businessnews 18 Aug 2026); 10 Sep 2026: Constitutional Council suspended implementation of a law (headline truncated — which law: UNVERIFIED); 15–16 Sep 2026 confidence debate (§2) | Google News RSS GN-H, GN-I; lp.gov.lb Id=36998 |
| Municipal elections | Held 4–25 May 2025 (first since 2016). The Arab News item "Parliament postpones municipal elections for a second time" dated 1 Sep 2026 is the April 2023 story re-indexed — ignore | https://en.wikipedia.org/wiki/2025_in_Lebanon ("4 May – 2025 Lebanese municipal elections") |
| Supervisory Commission for Elections | Appointed by the cabinet on 12 Dec 2025 (MTV "تعيين هيئة الإشراف على الانتخابات... مَن تضم؟"; Janoubia 12 Dec 2025); members' names not captured | GN-J |

Date-stamp: 2026-09-18.

---

## 4. Senior appointments since 1 January 2025

### 4.1 Timeline (verified rows)

Conventions: "Cabinet DD Mon YYYY" = decision of the Council of Ministers announced by Information Minister Paul Morcos
at that session; the implementing decree number is given only when a source states it (most do not — see §6).
Arabic names are given as printed in the cited headline. "GN-n" = Google News RSS query listed in §4.4, fetched
2026-09-18; "headline only" means the article body was not opened. Predecessor is UNVERIFIED unless a source names it.

| Date | Position | Person | Predecessor | Instrument | Source |
|---|---|---|---|---|---|
| 2025-01-09 | President of the Republic | Joseph Aoun | Michel Aoun (vacancy 2022-10-31 → 2025-01-09) | Election by Parliament, 2nd round | https://en.wikipedia.org/wiki/2025_in_Lebanon |
| 2025-01-10 | Army Commander (acting) | The Army Chief of Staff (name not in headline — UNVERIFIED) | Joseph Aoun | Decision of the Minister of National Defence | Naharnet 10 Jan 2025 "Army chief of staff named as acting army commander" (GN-1, headline only) |
| 2025-01-13 | Prime Minister-designate | Nawaf Salam | Najib Mikati (caretaker) | Binding parliamentary consultations (84/128) | https://en.wikipedia.org/wiki/2025_in_Lebanon |
| 2025-02-08 | Council of Ministers (24 members) | see part 02 §0 | Mikati government | Decrees 52 and 53 of 8-2-2025 | https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=13587 |
| 2025-03-13 | Commander of the Lebanese Army | Gen. Rodolphe Haykal (رودولف هيكل; Wikidata Q133256703 "Rudolph Haikal") | Joseph Aoun (acting: Chief of Staff) | Cabinet 13 Mar 2025; promotion and appointment decrees issued by the Presidency the same day ("رئاسة الجمهورية تصدر مراسيم التعيينات والترقيات الأمنية", almodon; "صدور مراسيم الترقية والتعيين لقادة الأجهزة الأمنية الجدد", Elnashra) — decree numbers UNVERIFIED | GN-2; The New Arab 13 Mar 2025; https://en.wikipedia.org/wiki/2025_in_Lebanon |
| 2025-03-13 | Director General of the Internal Security Forces | Maj. Gen. Raed Abdallah (رائد عبدالله) | Imad Osman (Al Akhbar 12 Feb 2025 "من سيخلف عثمان وحمّود") | same decrees | GN-2 (An-Nahar 13 Mar 2025 "من هو مدير عام قوى الأمن الداخلي اللواء رائد عبدالله؟") |
| 2025-03-13 | Director General of General Security | Maj. Gen. Hassan Choucair (حسن شقير) | UNVERIFIED (an acting DG since 2023) | same decrees | GN-2 (MTV 13 Mar 2025 "اللواء شقير إلى الأمن العام"; Janoubia 14 Mar 2025) |
| 2025-03-13 | Director General of State Security | Brig. Gen. Edgar Lawandos (إدغار لاوندوس) | UNVERIFIED | same decrees | GN-3 (IMLebanon 13 Mar 2025 "العميد ادغار لوندوس مدير عام امن الدولة"; Kataeb 14 Mar 2025) |
| 2025-03-19 | Head of the ISF Information Branch | Brig. Mahmoud Qabrasli (محمود قبرصلي) | Hammoud (first name UNVERIFIED; Al Akhbar 12 Feb 2025) | ISF/Interior appointment (instrument UNVERIFIED) | GN-4 (An-Nahar 19 Mar 2025; Lebanon Debate 19 Mar 2025) |
| 2025-03-20 | — (procedure) | Appointments mechanism (آلية التعيينات) adopted | — | Cabinet 20 Mar 2025 | Naharnet 20 Mar 2025 "Govt. approves appointments mechanism" (GN-5); part 01 §3.2 |
| 2025-03-25 | Chairman-DG of Ogero (dismissal) | Imad Kreidieh (عماد كريدية) removed by Telecom Minister Charles Hage | — | Ministerial decision | Al Akhbar 25 Mar 2025 "شارل الحاج يقيل كريدية" (GN-6, headline only) |
| 2025-03-27 | Governor of Banque du Liban | Karim Souaid (كريم سعيد) | Wassim Mansouri (acting governor, first vice-governor) — label UNVERIFIED | Cabinet vote 27 Mar 2025 (17 votes, LBCI); appointment decree number UNVERIFIED (document published by نافذة العرب) | GN-7; GN-8 (LBCI "Karim Souaid secures 17 votes"); https://en.wikipedia.org/wiki/2025_in_Lebanon |
| 2025-03-27 | Prosecutor General at the Court of Cassation (بالأصالة) | Judge Jamal Hajjar (جمال الحجار) | himself (acting) | Cabinet 27 Mar 2025 | GN-9 (Morcos statement, 27 Mar 2025) |
| 2025-03-27 | President of the State Council (مجلس شورى الدولة) | Judge Youssef Gemayel (يوسف الجميل) | Judge Fadi Elias (فادي إلياس) | Cabinet 27 Mar 2025 | GN-9 |
| 2025-04-08/09 | Higher Judicial Council — 4 members (first time 4 women) | names not in headlines | vacancies (quorum lost) | Decree (number UNVERIFIED); oath before Aoun 9 Apr 2025 | GN-10 (Lebanon Debate 8 Apr 2025; An-Nahar 11 Apr 2025 "مجلس القضاء الأعلى يكتمل عقده") |
| 2025-05-14 | President of the CDR | Mohammad Ali Qabbani (محمد قباني) | UNVERIFIED | Cabinet 14 May 2025 | GN-11; Businessnews 15 May 2025 |
| 2025-05-14 | Governor of North Lebanon (removal) | Ramzi Nohra (رمزي نهرا) placed at the disposal of the Interior Minister | — | Cabinet 14 May 2025 | GN-11 (Janoubia 14 May 2025); successor UNVERIFIED |
| 2025-05-15 | Higher Judicial Council — 2 further members | Rizkallah and Dakroub (first names not in headline) | — | Decree (UNVERIFIED) | GN-10 (موقع لبنان الكبير 15 May 2025) |
| 2025-05-29 | Secretary General of the CDR | Ghassan Khairallah (غسان خيرالله) | UNVERIFIED | Cabinet 29 May 2025 | GN-12 (Elnashra 29 May 2025) |
| 2025-05-29 | Chairman-DG of Ogero | Ahmad Oueidat (أحمد عويدات) | Imad Kreidieh | Cabinet 29 May 2025 | GN-13 (mtv 29 May 2025); GN-12 |
| 2025-05-29 | Director General of Finance (confirmed بالأصالة) | Georges Maarawi (جورج معراوي) | himself (acting) | Cabinet 29 May 2025 | GN-8 (LBCI 29 May 2025) |
| 2025-06-16 | Ambassadors — diplomatic permutations (التشكيلات الدبلوماسية) | Names published by MTV ("موقع mtv ينفرد بنشر مرسوم التشكيلات الدبلوماسيّة بالأسماء"); 5 from outside the cadre | — | Cabinet 16 Jun 2025; decree (number UNVERIFIED; "قابل للطعن", Al Akhbar 19 Jun 2025) | GN-14; Naharnet 16 Jun 2025 |
| 2025-07-11 | Vice-Governors of Banque du Liban (4) | Wassim Mansouri and Salim Chahine confirmed; Bou Nassar and Chinozian new (first names not in headline) | previous vice-governors (2 replaced) | Cabinet 11 Jul 2025 | GN-15 (المركزية 13 Jul 2025 "تثبيت منصوري وشاهين ودخول بو نصار وشينوزيان") |
| 2025-07-11 | Banking Control Commission — chair and members | Chair Mazen Soueid (مازن سويد); member Nader Haddad (نادر حداد) among others | — | Cabinet 11 Jul 2025 | GN-16 (almodon 11 Jul 2025 "سويد لـ'المدن'"; Lebanon 24 "من هم الرئيس والأعضاء الجدُد"; المركزية on Haddad) |
| 2025-07-11 | Financial Public Prosecutor | Judge Maher Cheaito (ماهر شعيتو) | UNVERIFIED | Cabinet 11 Jul 2025 | GN-8 (LBCI); GN-13 (mtv) |
| 2025-07-11 | Tele Liban board | Chair Elissar Naddaf Geagea (إليسار نداف جعجع) | (a 25 Mar 2025 report named Bassam Abou Zeid as chair — superseded/UNVERIFIED) | Cabinet 11 Jul 2025 | GN-17 (MTV "مجلس إدارة جديد لتلفزيون لبنان... ونداف رئيساً"); NNA year-end item (GN-18) |
| 2025-07-17 | Head of the civil-aviation regulatory authority | Captain Mohammad Aziz | — (new body) | Cabinet 17 Jul 2025 | GN-13; Businessnews 18 Jul 2025 "Cabinet forms two regulatory authorities" |
| 2025-08-01/05 | Judicial permutations (~524 judges) | — | — | **Decree 823 of 5 Aug 2025**, signed by the PM 1 Aug and by the President 5 Aug 2025 | GN-19 (Lebanon Debate 5 Aug 2025 quoting NNA; صوت بيروت 1 Aug 2025); L'Orient Today 5 Aug 2025 |
| 2025-08-12 | Army intelligence — Beirut southern-suburb office | Col. Samer Hamadeh replaces Brig. Maher Raad | Maher Raad | Army command decision | GN-20 (Lebanon 24, Janoubia 14 Aug 2025) |
| 2025-08-13 | DG of Rafik Hariri University Hospital | name not in headline | UNVERIFIED | Cabinet 13 Aug 2025 | GN-17 (Al Akhbar) |
| 2025-09-11 | Electricity Regulatory Authority (first ever board) | Chair Marwan Jamal (مروان جمال; Elnashra's headline printed "محمد جمال") + members | — (body dormant since 2002) | Cabinet 11 Sep 2025 | GN-21; Businessnews 11 Sep 2025; L'Orient Today 11 Sep 2025 |
| 2025-09-11 | Telecommunications Regulatory Authority | Chair Jenny Gemayel (جيني الجميل) + members | — | Cabinet 11 Sep 2025 | GN-21 |
| 2025-09-12 | Judicial Council (المجلس العدلي) members | 5 judges (names not captured) | expired council | Decree signed by the Justice Minister 12 Sep 2025 | GN-22 (An-Nahar, Elnashra, LBCI 12 Sep 2025) |
| 2025-10-08 | Court of Audit — four directors general | names not in headline | — | Cabinet (UNVERIFIED) | GN-23 (الراي 8 Oct 2025) |
| 2025-10-23 | Capital Markets Authority — 3 members | incl. Mahmoud Jebai (محمود جباعي) | — | Cabinet 23 Oct 2025; sworn in 30 Dec 2025 | GN-24 (MTV 23 Oct 2025); NNA 30 Dec 2025 (GN-18) |
| 2025-10-24 | National Food Safety Authority | members (names not captured) | — | Cabinet 24 Oct 2025 | GN-25 (Elnashra) |
| 2025-11-06 | Port of Beirut temporary management committee | Chair Marwan Nafi (مروان نافي) + 6 members | UNVERIFIED | Cabinet 6 Nov 2025 | GN-26 (Elnashra; almodon "مؤقتة منذ 35 عاماً") |
| 2025-11-10 | Central Inspection — board completed | president Georges Attieh (جورج عطية) continuing | — | Cabinet (dates UNVERIFIED) | GN-27 (MTV 10 Nov 2025 "اجتماع لهيئة التفتيش المركزي بعد اكتمال التعيينات") |
| 2025-11-13 | DG of the Ministry of Industry | Adel Gerges al-Shabab (عادل جرجس الشباب) | UNVERIFIED | Cabinet 13 Nov 2025 | GN-25 (Elnashra) |
| 2025-11-27 | General Authority of Museums — members | — | — | Cabinet 27 Nov 2025 | GN-28 (Elnashra) |
| 2025-12-04 | Ambassador to Syria | Henry Kastoun (هنري كستون) — first since 2021 | vacant 4 years | Cabinet Oct 2025; decree Dec 2025 | GN-29 (The New Arab 24 Oct 2025; Al Jazeera 4 Dec 2025) |
| 2025-12-12 | Supervisory Commission for Elections | members (not captured) | — | Cabinet 12 Dec 2025 | GN-J |
| 2025-12-22 | IDAL board | Chair Majed Mneimneh (ماجد منيمنة) + 6 members | — | Cabinet 22 Dec 2025 | GN-30 (Elnashra 22 Dec 2025; Businessnews 23 Dec 2025) |
| 2025-12-29 | Ambassadors to Syria, Poland, South Korea | received by Aoun before departure (names not in headline) | — | (June 2025 decree) | NNA 29 Dec 2025 (GN-18) |
| 2026-01-12 | Chairman of the board, Casino du Liban | Charles Ghostine (شارل غسطين) — elected by the board | Roland Khoury (رولان الخوري; detained July 2025) | Board election 12 Jan 2026 | GN-31 (NNA, Elnashra, Kataeb 12 Jan 2026) |
| 2026-01-15 | President of the Higher Council of Customs | Brig. Misbah Khalil (مصباح خليل) | UNVERIFIED | Cabinet 15 Jan 2026 (decree text published by Lebanon Debate 17 Jan 2026) | GN-32 (An-Nahar, المركزية, Elnashra 15 Jan 2026) |
| 2026-01-15 | Director General of Customs | Gracia Azzi (غراسيا قزي) | UNVERIFIED | same | GN-32; An-Nahar 15 Jan 2026 "مديرة عامة جديدة للجمارك"; Justice Minister's reservation (Elsiyasa 16 Jan 2026) |
| 2026-01-30 | Director General of Civil Defence (بالأصالة) | Imad Khreich (عماد خريش) | himself (acting) — UNVERIFIED | Cabinet (المركزية 30 Jan 2026) | GN-33 |
| 2026-02-13/18 | State Council — four chamber presidents | names not captured | — | Decree signed by the Finance Minister after a 3-month delay | GN-9 (Legal Agenda 13 Feb 2026; An-Nahar 18 Feb 2026) |
| 2026-02-16 | Court of Audit — chamber president | Judge Wassim Abou Saad (وسيم أبو سعد) | — | Decree (UNVERIFIED) | GN-23 (Lebanon Debate 16 Feb 2026) |
| 2026-03-05 | Economic, Social and Environmental Council — members | president Charles Arbid (شارل عربيد) continuing (An-Nahar 7 Apr 2026) | — | Cabinet 5 Mar 2026 | GN-34 |
| 2026-04-02 | (batch) appointments + military grant | not itemised in headlines | — | Cabinet 2 Apr 2026 | GN-35 (Al Akhbar; Lebanon Debate) |
| 2026-04-30 | Prosecutor General at the Court of Cassation | Judge Ahmad Rami al-Hajj (أحمد رامي الحاج) | Jamal Hajjar (retired end April 2026) | Cabinet 30 Apr 2026 (Baabda) | GN-36 (L'Orient Today 30 Apr 2026; dailybeirut 30 Apr 2026; akhbaralyawm 17 Apr 2026 on the retirement) |
| 2026-04-30 (date UNVERIFIED) | Head of Judicial Inspection | Judge Osama Mneimneh (أسامة منيمنة) | UNVERIFIED | Cabinet | GN-36 (mtv English item, mis-dated; headline only) |
| 2026-05-22 | DG of Public Health | Hala al-Mawla (هالة المولى) | UNVERIFIED | Cabinet 22 May 2026 | GN-37 (Elnashra quoting Al Jadeed; L'Orient Today 22 May 2026; LBCI) |
| 2026-05-22 | DG of Social Affairs | Wiam Abou Hamdan (وئام أبو حمدان) | UNVERIFIED | Cabinet 22 May 2026 — preceded by the public call "إعلان لتعيين في وظيفة مدير عام الشؤون الإجتماعية" on pcm.gov.lb (pageid 26829, cached Feb 2026) | GN-37 |
| 2026-05-22 | DG of Oil Installations | Maurice Qarqafi (موريس قرقفي) | UNVERIFIED | Cabinet 22 May 2026 | GN-37 |
| 2026-05-22 | DG of Land and Maritime Transport | Brig. Gen. Mazen Bassbous (مازن بصبوص) | UNVERIFIED | Cabinet 22 May 2026 | GN-13 (mtv English, headline only) + GN-37 |
| 2026-05-22 | Water establishments — board members (several governorates) | — | — | Cabinet 22 May 2026 | GN-37 |
| 2026-06-25 | Employees' Cooperative; ERA members; CNRS Secretary General; petroleum sector | names not captured | — | Cabinet 25 Jun 2026 | GN-38 (Elnashra, akhbaralyawm 25 Jun 2026) |
| 2026-07-09 | President of the Beirut International Airport establishment | Mohammad Abdel Razzaq Shatila (محمد عبد الرزاق شاتيلا) | UNVERIFIED | Cabinet 9 Jul 2026 | GN-39 (MTV, Lebanon Debate, NBN via lebanonon, 9 Jul 2026) |
| 2026-07-09 | DG of Higher Education (بالأصالة) | Dr Mazen al-Khatib (مازن الخطيب) | himself (acting) | Cabinet 9 Jul 2026 | GN-39; Al Akhbar 10 Jul 2026 |
| 2026-07-09 | President of the Electricity Regulatory Authority | Ziad Samkieh (زياد سمكية) | Marwan Jamal (appointed 11 Sep 2025 — a ten-month tenure) | Cabinet 9 Jul 2026 (Energy Ministry, 24 Jun 2026: "التغيير في عضوية الهيئة الناظمة للكهرباء تدبير إداري") | GN-39; GN-21 |
| 2026-07-23 | DG of the Public Establishment for Consumer Markets | Samer al-Khond (سامر الخوند) | UNVERIFIED | Cabinet 23 Jul 2026 | GN-40 (agriculture.gov.lb 24 Jul 2026; akhbaralyawm) |
| 2026-08-06/07 | NSSF board of directors (first in 19 years); Beirut airport company | members listed by almodon | expired board | Cabinet (almodon 7 Aug 2026; Al-Binaa 8 Aug 2026) | GN-41 |
| 2026-08-17 | Chairman of the NSSF board | Bechara al-Asmar (بشارة الأسمر) — elected at the board's first session | — | Board election 17 Aug 2026 | GN-41 (Janoubia, Kataeb 17 Aug 2026) |
| 2026-09-10 | President of the Civil Service Board | Nisrine Machmouchi (نسرين مشموشي) — sworn in before Aoun and Salam | UNVERIFIED | Cabinet (date UNVERIFIED); oath 10 Sep 2026 | GN-42 (Al Jadeed 10 Sep 2026) |
| 2026-09-10 | Public Procurement Authority — president and members | names UNVERIFIED | vacancy since 9 Jul 2026 (president's term ended without replacement) | Cabinet (date UNVERIFIED); oath 10 Sep 2026 | GN-43 (المركزية 10 Sep 2026 "عون لمناسبة قسم يمين رئيس وأعضاء هيئة الشراء العام"; An-Nahar 9 Jul 2026; Legal Agenda 31 Jul 2026) |
| 2026-09-10 | Competition Council — 5 members | Adnan Rammal, Jamil Jleilaty, Pascal Daher, Elian Nehme, Anis Bou Diab (عدنان رمال، جميل جليلاتي، باسكال ضاهر، ايليان نعمة، انيس بو دياب) | — (new body, Law 281/2022) | Cabinet 10 Sep 2026 | GN-A (Alkalima Online 10 Sep 2026); government commissioner Mohammed Abou Haidar per mtv English (UNVERIFIED) |
| 2026-09-17 | Chairman of the board, Électricité du Liban | Nassib Nasr (نسيب نصر) | expired board (akhbaralyawm 3 Dec 2025 "كهرباء لبنان... منتهية الولاية") | Cabinet 17 Sep 2026; DG Kamal Hayek (كمال حايك) retained | GN-44 (Lebanon Debate, Elnashra, LBCI, ekherelakhbar 17 Sep 2026) |
| 2026-09-17 | Secretary General of the Higher Council for Privatisation and PPP | Jocelyne Jabbour (جوسلين جبور) | UNVERIFIED | Cabinet 17 Sep 2026 | GN-45 (MTV 17 Sep 2026) |
| 2026-09-17 | President of the Centre for Educational Research and Development (CRDP) | Hiyam Ishak (هيام اسحق) | UNVERIFIED | Cabinet 17 Sep 2026 | GN-45 |
| 2026-09-17 | 11 heads of service (رؤساء مصلحة) at the Ministry of Industry, بالأصالة | — | acting heads | Ministerial decision (Issa el-Khoury) | GN-F (Elnashra, Lebanon 24 17 Sep 2026) |

Bodies asked for but with **no change found** since 1 Jan 2025: Middle East Airlines (chairman Mohamad El-Hout continuing — The New Arab 13 Jun 2025; mtv 30 Jul 2026, GN-46); Court of Audit presidency (no appointment headline; UNVERIFIED); National Anti-Corruption Commission (functioning — annual report delivered to Aoun 8 Jul 2026 and Salam 31 Jul 2026, GN-47 — membership unchanged, president's name not captured); Beirut governor Marwan Abboud (replacement contested Aug–Sep 2026, GN-48, no change); Constitutional Council (see §4.2).

### 4.2 Vacancies and long-standing acting arrangements as of 2026-09-18

| Body / post | Situation on 2026-09-18 | Source |
|---|---|---|
| Grade-one posts (الفئة الأولى) generally | 62 of 149 grade-one posts vacant (Al-Liwaa, 24 Dec 2025); the 2025–2026 waves above filled roughly a dozen; "أزمة الشغور في وظائف الفئة الأولى" still the frame on 10 Sep 2026 (Janoubia; Kataeb) | GN-49 |
| Constitutional Council | Members' term expired (Kataeb 1 Sep 2025 "ولاية الدستوري انتهت"); 61 candidates screened (Al Akhbar 10 Jul 2025); no appointment/election of new members found; the Council kept ruling (Decision 7/2026, Apr 2026, President Tannous Mechleb; LU decision 3 Sep 2026; suspension 10 Sep 2026) — continuing under the expired mandate. **Renewal status UNVERIFIED** | GN-50; part 01 §5.4 |
| Public Procurement Authority | President's term ended 9 Jul 2026 with no successor; authority "بلا رئيس وبلا هيئة" (Legal Agenda 31 Jul 2026); new president and members sworn in 10 Sep 2026 (names UNVERIFIED) | GN-43 |
| Lebanese University presidency | July 2026 law extending President Bassam Badran's term annulled by the Constitutional Council 3 Sep 2026 ("ثبت حق التجديد لمرة واحدة"); legality of a second-term candidacy debated (almodon 16 Sep 2026); deans' council also affected ("طار مجلس العمداء") — **status unresolved** | GN-H |
| Governor of North Lebanon | Ramzi Nohra removed 14 May 2025; successor not found in press — UNVERIFIED whether filled or run by an acting governor | GN-11 |
| Governor of Beirut | Marwan Abboud in post (since 2020); replacement contested between the Presidency and the Greek Orthodox Church (Al Akhbar 1 Sep 2026 "شد حبال بين عون وعودة"; 17 Aug 2026 "محافظون جدد؟") | GN-48 |
| Higher Judicial Council / Court of Cassation | First President Souheil Abboud (سهيل عبود) in post; open conflict with Baabda over partial permutations (Al Akhbar 10 Aug 2026 "عبّود يرفض التشكيلات الجزئية"; 13 Sep 2026 "عبود حاكماً عسكرياً"); ~30 posts vacant through retirements a year after Decree 823 (Janoubia 25 Jul 2026) | GN-19; GN-36 |
| National Media Council (المجلس الوطني للإعلام) | Listed as expired ("منتهية الولاية") 3 Dec 2025; still described as depleted 12 May 2026 (Legal Agenda "ماذا تبقّى من المجلس الوطني للإعلام؟"); no renewal found — UNVERIFIED | GN-28 |
| Intra Investment Co. board | Listed as expired 3 Dec 2025; no renewal found | GN-28 |
| Army Director of Intelligence | Holder's name not captured in 2025–2026 headlines — UNVERIFIED | GN-20 |
| Higher Relief Council — Secretary General | Not captured — UNVERIFIED | — |
| Ogero | Statutory transition to "Liban Telecom" being prepared (Janoubia 25 Feb 2026 "أوجيرو ستختفي فجأة وتحل محلها ليبان تيليكوم") | GN-33 |
| Acting → substantive (بالإنابة/بالتكليف → بالأصالة) confirmations in the period | Georges Maarawi (Finance DG, 29 May 2025); Jamal Hajjar (Prosecutor General, 27 Mar 2025); Imad Khreich (Civil Defence DG, 30 Jan 2026); Mazen al-Khatib (Higher Education DG, 9 Jul 2026); 11 heads of service at Industry (17 Sep 2026) — these are the "acting" tenures the model must end and re-open | rows above |
| Banque du Liban vice-governors | Governor asked to replace all four (Kataeb 12 Jun 2025); cabinet replaced two on 11 Jul 2025 | GN-15 |

### 4.3 What the timeline shows about instruments

- Cabinet decisions are announced the same evening by the Information Minister and echoed by 6–12 outlets; the
  decree that executes them is signed days to weeks later and is almost never numbered in press. Only three
  instruments in the whole period carry a number in the sources above: Decrees 52/53 (8 Feb 2025), Decree 823
  (5 Aug 2025) and the Parliament-related Decrees 2438/2591 and Law 41/2026 (part 01).
- The Presidency site alone publishes numbered, dated decrees per tenure (https://www.presidency.gov.lb/former-directors).
- Boards elect their own chair after the cabinet appoints members (Casino du Liban 12 Jan 2026; NSSF 17 Aug 2026):
  two dated events for one seat.
- Ministers appoint below grade one by ministerial decision (Ogero dismissal 25 Mar 2025; 11 heads of service 17 Sep 2026;
  ISF Information Branch 19 Mar 2025): a second instrument type ("قرار وزاري") besides decrees.

### 4.4 Google News RSS queries used (all fetched 2026-09-18)

Template: `https://news.google.com/rss/search?q=<query>&hl=ar&gl=LB&ceid=LB:ar` (Arabic) or `…&hl=en-US&gl=LB&ceid=US:en` (English).
`after:`/`before:` operators are part of the query string.

| Ref | Lang | Query | URL |
|---|---|---|---|
| GN-A | ar | `عدنان ضاهر after:2025-06-01` | https://news.google.com/rss/search?q=%D8%B9%D8%AF%D9%86%D8%A7%D9%86%20%D8%B6%D8%A7%D9%87%D8%B1%20after%3A2025-06-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-B | ar | `الأمين العام لمجلس النواب عدنان ضاهر` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D8%A3%D9%85%D9%8A%D9%86%20%D8%A7%D9%84%D8%B9%D8%A7%D9%85%20%D9%84%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%86%D9%88%D8%A7%D8%A8%20%D8%B9%D8%AF%D9%86%D8%A7%D9%86%20%D8%B6%D8%A7%D9%87%D8%B1&hl=ar&gl=LB&ceid=LB:ar |
| GN-C | en | `site:naharnet.com cabinet appoints` | https://news.google.com/rss/search?q=site%3Anaharnet.com%20cabinet%20appoints&hl=en-US&gl=LB&ceid=US:en |
| GN-D | ar | `استقالة وزير الخارجية يوسف رجي` | https://news.google.com/rss/search?q=%D8%A7%D8%B3%D8%AA%D9%82%D8%A7%D9%84%D8%A9%20%D9%88%D8%B2%D9%8A%D8%B1%20%D8%A7%D9%84%D8%AE%D8%A7%D8%B1%D8%AC%D9%8A%D8%A9%20%D9%8A%D9%88%D8%B3%D9%81%20%D8%B1%D8%AC%D9%8A&hl=ar&gl=LB&ceid=LB:ar |
| GN-E | en | `Lebanese foreign minister quits` | https://news.google.com/rss/search?q=Lebanese%20foreign%20minister%20quits&hl=en-US&gl=LB&ceid=US:en |
| GN-F | ar | `مجلس الوزراء عيّن تعيين رئيسا مديرا عاما after:2026-07-01 before:2026-09-19` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%20%D8%B9%D9%8A%D9%91%D9%86%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%D8%A7%20%D9%85%D8%AF%D9%8A%D8%B1%D8%A7%20%D8%B9%D8%A7%D9%85%D8%A7%20after%3A2026-07-01%20before%3A2026-09-19&hl=ar&gl=LB&ceid=LB:ar |
| GN-G | ar | `انتخابات فرعية البقاع الغربية راشيا مقعد سكاف after:2025-12-13` | https://news.google.com/rss/search?q=%D8%A7%D9%86%D8%AA%D8%AE%D8%A7%D8%A8%D8%A7%D8%AA%20%D9%81%D8%B1%D8%B9%D9%8A%D8%A9%20%D8%A7%D9%84%D8%A8%D9%82%D8%A7%D8%B9%20%D8%A7%D9%84%D8%BA%D8%B1%D8%A8%D9%8A%D8%A9%20%D8%B1%D8%A7%D8%B4%D9%8A%D8%A7%20%D9%85%D9%82%D8%B9%D8%AF%20%D8%B3%D9%83%D8%A7%D9%81%20after%3A2025-12-13&hl=ar&gl=LB&ceid=LB:ar |
| GN-H | ar | `رئيس الجامعة اللبنانية بدران المجلس الدستوري after:2026-09-01` | https://news.google.com/rss/search?q=%D8%B1%D8%A6%D9%8A%D8%B3%20%D8%A7%D9%84%D8%AC%D8%A7%D9%85%D8%B9%D8%A9%20%D8%A7%D9%84%D9%84%D8%A8%D9%86%D8%A7%D9%86%D9%8A%D8%A9%20%D8%A8%D8%AF%D8%B1%D8%A7%D9%86%20%D8%A7%D9%84%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D8%AF%D8%B3%D8%AA%D9%88%D8%B1%D9%8A%20after%3A2026-09-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-I | en | `Lebanon National Human Rights Commission` | https://news.google.com/rss/search?q=Lebanon%20National%20Human%20Rights%20Commission&hl=en-US&gl=LB&ceid=US:en |
| GN-J | ar | `هيئة الإشراف على الانتخابات تعيين أعضاء` | https://news.google.com/rss/search?q=%D9%87%D9%8A%D8%A6%D8%A9%20%D8%A7%D9%84%D8%A5%D8%B4%D8%B1%D8%A7%D9%81%20%D8%B9%D9%84%D9%89%20%D8%A7%D9%84%D8%A7%D9%86%D8%AA%D8%AE%D8%A7%D8%A8%D8%A7%D8%AA%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%A3%D8%B9%D8%B6%D8%A7%D8%A1&hl=ar&gl=LB&ceid=LB:ar |
| GN-1 | en | `Lebanon army chief of staff appointed` | https://news.google.com/rss/search?q=Lebanon%20army%20chief%20of%20staff%20appointed&hl=en-US&gl=LB&ceid=US:en |
| GN-2 | ar | `مجلس الوزراء التعيينات الأمنية قائد الجيش المدير العام للأمن العام أمن الدولة قوى الأمن الداخلي after:2025-03-10 before:2025-03-20` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%20%D8%A7%D9%84%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%D8%A7%D8%AA%20%D8%A7%D9%84%D8%A3%D9%85%D9%86%D9%8A%D8%A9%20%D9%82%D8%A7%D8%A6%D8%AF%20%D8%A7%D9%84%D8%AC%D9%8A%D8%B4%20%D8%A7%D9%84%D9%85%D8%AF%D9%8A%D8%B1%20%D8%A7%D9%84%D8%B9%D8%A7%D9%85%20%D9%84%D9%84%D8%A3%D9%85%D9%86%20%D8%A7%D9%84%D8%B9%D8%A7%D9%85%20%D8%A3%D9%85%D9%86%20%D8%A7%D9%84%D8%AF%D9%88%D9%84%D8%A9%20%D9%82%D9%88%D9%89%20%D8%A7%D9%84%D8%A3%D9%85%D9%86%20%D8%A7%D9%84%D8%AF%D8%A7%D8%AE%D9%84%D9%8A%20after%3A2025-03-10%20before%3A2025-03-20&hl=ar&gl=LB&ceid=LB:ar |
| GN-3 | ar | `مدير عام أمن الدولة تعيين اللواء after:2025-03-10 before:2025-03-25` | https://news.google.com/rss/search?q=%D9%85%D8%AF%D9%8A%D8%B1%20%D8%B9%D8%A7%D9%85%20%D8%A3%D9%85%D9%86%20%D8%A7%D9%84%D8%AF%D9%88%D9%84%D8%A9%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%A7%D9%84%D9%84%D9%88%D8%A7%D8%A1%20after%3A2025-03-10%20before%3A2025-03-25&hl=ar&gl=LB&ceid=LB:ar |
| GN-4 | ar | `تعيين رئيس شعبة المعلومات` | https://news.google.com/rss/search?q=%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%20%D8%B4%D8%B9%D8%A8%D8%A9%20%D8%A7%D9%84%D9%85%D8%B9%D9%84%D9%88%D9%85%D8%A7%D8%AA&hl=ar&gl=LB&ceid=LB:ar |
| GN-5 | en | `site:naharnet.com cabinet appoints` | https://news.google.com/rss/search?q=site%3Anaharnet.com%20cabinet%20appoints&hl=en-US&gl=LB&ceid=US:en |
| GN-6 | ar | `مجلس الوزراء عيّن تعيين رئيسا مديرا عاما after:2025-01-01 before:2025-04-01` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%20%D8%B9%D9%8A%D9%91%D9%86%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%D8%A7%20%D9%85%D8%AF%D9%8A%D8%B1%D8%A7%20%D8%B9%D8%A7%D9%85%D8%A7%20after%3A2025-01-01%20before%3A2025-04-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-7 | ar | `مرسوم رقم تعيين كريم سعيد حاكما لمصرف لبنان` | https://news.google.com/rss/search?q=%D9%85%D8%B1%D8%B3%D9%88%D9%85%20%D8%B1%D9%82%D9%85%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D9%83%D8%B1%D9%8A%D9%85%20%D8%B3%D8%B9%D9%8A%D8%AF%20%D8%AD%D8%A7%D9%83%D9%85%D8%A7%20%D9%84%D9%85%D8%B5%D8%B1%D9%81%20%D9%84%D8%A8%D9%86%D8%A7%D9%86&hl=ar&gl=LB&ceid=LB:ar |
| GN-8 | en | `site:lbcgroup.tv cabinet appoints` | https://news.google.com/rss/search?q=site%3Albcgroup.tv%20cabinet%20appoints&hl=en-US&gl=LB&ceid=US:en |
| GN-9 | ar | `تعيين رئيس مجلس شورى الدولة` | https://news.google.com/rss/search?q=%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%20%D9%85%D8%AC%D9%84%D8%B3%20%D8%B4%D9%88%D8%B1%D9%89%20%D8%A7%D9%84%D8%AF%D9%88%D9%84%D8%A9&hl=ar&gl=LB&ceid=LB:ar |
| GN-10 | ar | `مجلس القضاء الأعلى تعيين أعضاء مرسوم` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%82%D8%B6%D8%A7%D8%A1%20%D8%A7%D9%84%D8%A3%D8%B9%D9%84%D9%89%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%A3%D8%B9%D8%B6%D8%A7%D8%A1%20%D9%85%D8%B1%D8%B3%D9%88%D9%85&hl=ar&gl=LB&ceid=LB:ar |
| GN-11 | ar | `تعيين رئيس مجلس الإنماء والإعمار` | https://news.google.com/rss/search?q=%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%20%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D8%A5%D9%86%D9%85%D8%A7%D8%A1%20%D9%88%D8%A7%D9%84%D8%A5%D8%B9%D9%85%D8%A7%D8%B1&hl=ar&gl=LB&ceid=LB:ar |
| GN-12 | ar | `مجلس الوزراء عيّن تعيين رئيسا مديرا عاما after:2025-04-01 before:2025-07-01` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%20%D8%B9%D9%8A%D9%91%D9%86%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%D8%A7%20%D9%85%D8%AF%D9%8A%D8%B1%D8%A7%20%D8%B9%D8%A7%D9%85%D8%A7%20after%3A2025-04-01%20before%3A2025-07-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-13 | en | `site:mtv.com.lb Cabinet appointed` | https://news.google.com/rss/search?q=site%3Amtv.com.lb%20Cabinet%20appointed&hl=en-US&gl=LB&ceid=US:en |
| GN-14 | ar | `مرسوم التشكيلات الدبلوماسية سفراء` | https://news.google.com/rss/search?q=%D9%85%D8%B1%D8%B3%D9%88%D9%85%20%D8%A7%D9%84%D8%AA%D8%B4%D9%83%D9%8A%D9%84%D8%A7%D8%AA%20%D8%A7%D9%84%D8%AF%D8%A8%D9%84%D9%88%D9%85%D8%A7%D8%B3%D9%8A%D8%A9%20%D8%B3%D9%81%D8%B1%D8%A7%D8%A1&hl=ar&gl=LB&ceid=LB:ar |
| GN-15 | ar | `نواب حاكم مصرف لبنان تعيين after:2025-07-01 before:2025-08-01` | https://news.google.com/rss/search?q=%D9%86%D9%88%D8%A7%D8%A8%20%D8%AD%D8%A7%D9%83%D9%85%20%D9%85%D8%B5%D8%B1%D9%81%20%D9%84%D8%A8%D9%86%D8%A7%D9%86%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20after%3A2025-07-01%20before%3A2025-08-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-16 | ar | `رئيس لجنة الرقابة على المصارف الجديد after:2025-07-10 before:2025-09-01` | https://news.google.com/rss/search?q=%D8%B1%D8%A6%D9%8A%D8%B3%20%D9%84%D8%AC%D9%86%D8%A9%20%D8%A7%D9%84%D8%B1%D9%82%D8%A7%D8%A8%D8%A9%20%D8%B9%D9%84%D9%89%20%D8%A7%D9%84%D9%85%D8%B5%D8%A7%D8%B1%D9%81%20%D8%A7%D9%84%D8%AC%D8%AF%D9%8A%D8%AF%20after%3A2025-07-10%20before%3A2025-09-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-17 | ar | `مجلس الوزراء عيّن تعيين رئيسا مديرا عاما after:2025-07-01 before:2025-10-01` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%20%D8%B9%D9%8A%D9%91%D9%86%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%D8%A7%20%D9%85%D8%AF%D9%8A%D8%B1%D8%A7%20%D8%B9%D8%A7%D9%85%D8%A7%20after%3A2025-07-01%20before%3A2025-10-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-18 | en | `site:nna-leb.gov.lb appointed` | https://news.google.com/rss/search?q=site%3Anna-leb.gov.lb%20appointed&hl=en-US&gl=LB&ceid=US:en |
| GN-19 | ar | `مرسوم التشكيلات القضائية رقم` | https://news.google.com/rss/search?q=%D9%85%D8%B1%D8%B3%D9%88%D9%85%20%D8%A7%D9%84%D8%AA%D8%B4%D9%83%D9%8A%D9%84%D8%A7%D8%AA%20%D8%A7%D9%84%D9%82%D8%B6%D8%A7%D8%A6%D9%8A%D8%A9%20%D8%B1%D9%82%D9%85&hl=ar&gl=LB&ceid=LB:ar |
| GN-20 | ar | `تعيين مدير المخابرات في الجيش العميد after:2025-03-14 before:2025-09-01` | https://news.google.com/rss/search?q=%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D9%85%D8%AF%D9%8A%D8%B1%20%D8%A7%D9%84%D9%85%D8%AE%D8%A7%D8%A8%D8%B1%D8%A7%D8%AA%20%D9%81%D9%8A%20%D8%A7%D9%84%D8%AC%D9%8A%D8%B4%20%D8%A7%D9%84%D8%B9%D9%85%D9%8A%D8%AF%20after%3A2025-03-14%20before%3A2025-09-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-21 | ar | `الهيئة الناظمة لقطاع الكهرباء رئيس جمال after:2025-09-01` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D9%87%D9%8A%D8%A6%D8%A9%20%D8%A7%D9%84%D9%86%D8%A7%D8%B8%D9%85%D8%A9%20%D9%84%D9%82%D8%B7%D8%A7%D8%B9%20%D8%A7%D9%84%D9%83%D9%87%D8%B1%D8%A8%D8%A7%D8%A1%20%D8%B1%D8%A6%D9%8A%D8%B3%20%D8%AC%D9%85%D8%A7%D9%84%20after%3A2025-09-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-22 | ar | `المجلس العدلي تعيين أعضاء` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D8%B9%D8%AF%D9%84%D9%8A%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%A3%D8%B9%D8%B6%D8%A7%D8%A1&hl=ar&gl=LB&ceid=LB:ar |
| GN-23 | ar | `تعيين رئيس ديوان المحاسبة` | https://news.google.com/rss/search?q=%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%20%D8%AF%D9%8A%D9%88%D8%A7%D9%86%20%D8%A7%D9%84%D9%85%D8%AD%D8%A7%D8%B3%D8%A8%D8%A9&hl=ar&gl=LB&ceid=LB:ar |
| GN-24 | ar | `هيئة الأسواق المالية تعيين أعضاء` | https://news.google.com/rss/search?q=%D9%87%D9%8A%D8%A6%D8%A9%20%D8%A7%D9%84%D8%A3%D8%B3%D9%88%D8%A7%D9%82%20%D8%A7%D9%84%D9%85%D8%A7%D9%84%D9%8A%D8%A9%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%A3%D8%B9%D8%B6%D8%A7%D8%A1&hl=ar&gl=LB&ceid=LB:ar |
| GN-25 | ar | `مجلس الوزراء عيّن تعيين رئيسا مديرا عاما after:2025-10-01 before:2026-01-01` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%20%D8%B9%D9%8A%D9%91%D9%86%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%D8%A7%20%D9%85%D8%AF%D9%8A%D8%B1%D8%A7%20%D8%B9%D8%A7%D9%85%D8%A7%20after%3A2025-10-01%20before%3A2026-01-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-26 | ar | `مرفأ بيروت اللجنة المؤقتة تعيين مجلس إدارة` | https://news.google.com/rss/search?q=%D9%85%D8%B1%D9%81%D8%A3%20%D8%A8%D9%8A%D8%B1%D9%88%D8%AA%20%D8%A7%D9%84%D9%84%D8%AC%D9%86%D8%A9%20%D8%A7%D9%84%D9%85%D8%A4%D9%82%D8%AA%D8%A9%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D9%85%D8%AC%D9%84%D8%B3%20%D8%A5%D8%AF%D8%A7%D8%B1%D8%A9&hl=ar&gl=LB&ceid=LB:ar |
| GN-27 | ar | `تعيين رئيس التفتيش المركزي` | https://news.google.com/rss/search?q=%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%20%D8%A7%D9%84%D8%AA%D9%81%D8%AA%D9%8A%D8%B4%20%D8%A7%D9%84%D9%85%D8%B1%D9%83%D8%B2%D9%8A&hl=ar&gl=LB&ceid=LB:ar |
| GN-28 | ar | `المجلس الوطني للإعلام تعيين أعضاء` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B7%D9%86%D9%8A%20%D9%84%D9%84%D8%A5%D8%B9%D9%84%D8%A7%D9%85%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%A3%D8%B9%D8%B6%D8%A7%D8%A1&hl=ar&gl=LB&ceid=LB:ar |
| GN-29 | en | `Lebanon ambassador Syria Damascus Lebanese appointed` | https://news.google.com/rss/search?q=Lebanon%20ambassador%20Syria%20Damascus%20Lebanese%20appointed&hl=en-US&gl=LB&ceid=US:en |
| GN-30 | ar | `مؤسسة المؤسسة العامة لتشجيع الاستثمارات تعيين` | https://news.google.com/rss/search?q=%D9%85%D8%A4%D8%B3%D8%B3%D8%A9%20%D8%A7%D9%84%D9%85%D8%A4%D8%B3%D8%B3%D8%A9%20%D8%A7%D9%84%D8%B9%D8%A7%D9%85%D8%A9%20%D9%84%D8%AA%D8%B4%D8%AC%D9%8A%D8%B9%20%D8%A7%D9%84%D8%A7%D8%B3%D8%AA%D8%AB%D9%85%D8%A7%D8%B1%D8%A7%D8%AA%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86&hl=ar&gl=LB&ceid=LB:ar |
| GN-31 | ar | `كازينو لبنان مجلس إدارة تعيين رئيس` | https://news.google.com/rss/search?q=%D9%83%D8%A7%D8%B2%D9%8A%D9%86%D9%88%20%D9%84%D8%A8%D9%86%D8%A7%D9%86%20%D9%85%D8%AC%D9%84%D8%B3%20%D8%A5%D8%AF%D8%A7%D8%B1%D8%A9%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3&hl=ar&gl=LB&ceid=LB:ar |
| GN-32 | ar | `المجلس الأعلى للجمارك تعيين رئيس` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D8%A3%D8%B9%D9%84%D9%89%20%D9%84%D9%84%D8%AC%D9%85%D8%A7%D8%B1%D9%83%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3&hl=ar&gl=LB&ceid=LB:ar |
| GN-33 | ar | `مجلس الوزراء عيّن تعيين رئيسا مديرا عاما after:2026-01-01 before:2026-04-01` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%20%D8%B9%D9%8A%D9%91%D9%86%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%D8%A7%20%D9%85%D8%AF%D9%8A%D8%B1%D8%A7%20%D8%B9%D8%A7%D9%85%D8%A7%20after%3A2026-01-01%20before%3A2026-04-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-34 | ar | `المجلس الاقتصادي والاجتماعي والبيئي رئيساً after:2026-03-01 before:2026-06-01` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D8%A7%D9%82%D8%AA%D8%B5%D8%A7%D8%AF%D9%8A%20%D9%88%D8%A7%D9%84%D8%A7%D8%AC%D8%AA%D9%85%D8%A7%D8%B9%D9%8A%20%D9%88%D8%A7%D9%84%D8%A8%D9%8A%D8%A6%D9%8A%20%D8%B1%D8%A6%D9%8A%D8%B3%D8%A7%D9%8B%20after%3A2026-03-01%20before%3A2026-06-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-35 | ar | `مجلس الوزراء تعيينات after:2026-04-01 before:2026-04-05` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%D8%A7%D8%AA%20after%3A2026-04-01%20before%3A2026-04-05&hl=ar&gl=LB&ceid=LB:ar |
| GN-36 | en | `Ahmad Rami Al-Hajj Prosecutor General Court of Cassation` | https://news.google.com/rss/search?q=Ahmad%20Rami%20Al-Hajj%20Prosecutor%20General%20Court%20of%20Cassation&hl=en-US&gl=LB&ceid=US:en |
| GN-37 | ar | `مجلس الوزراء عيّن تعيين رئيسا مديرا عاما after:2026-04-01 before:2026-07-01` | https://news.google.com/rss/search?q=%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%20%D8%B9%D9%8A%D9%91%D9%86%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%B1%D8%A6%D9%8A%D8%B3%D8%A7%20%D9%85%D8%AF%D9%8A%D8%B1%D8%A7%20%D8%B9%D8%A7%D9%85%D8%A7%20after%3A2026-04-01%20before%3A2026-07-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-38 | ar | `المجلس الوطني للبحوث العلمية تعيين أمين عام` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B7%D9%86%D9%8A%20%D9%84%D9%84%D8%A8%D8%AD%D9%88%D8%AB%20%D8%A7%D9%84%D8%B9%D9%84%D9%85%D9%8A%D8%A9%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%A3%D9%85%D9%8A%D9%86%20%D8%B9%D8%A7%D9%85&hl=ar&gl=LB&ceid=LB:ar |
| GN-39 | ar | `زياد سمكية رئيسا` | https://news.google.com/rss/search?q=%D8%B2%D9%8A%D8%A7%D8%AF%20%D8%B3%D9%85%D9%83%D9%8A%D8%A9%20%D8%B1%D8%A6%D9%8A%D8%B3%D8%A7&hl=ar&gl=LB&ceid=LB:ar |
| GN-40 | ar | `سامر الخوند مديرا عاما` | https://news.google.com/rss/search?q=%D8%B3%D8%A7%D9%85%D8%B1%20%D8%A7%D9%84%D8%AE%D9%88%D9%86%D8%AF%20%D9%85%D8%AF%D9%8A%D8%B1%D8%A7%20%D8%B9%D8%A7%D9%85%D8%A7&hl=ar&gl=LB&ceid=LB:ar |
| GN-41 | ar | `الصندوق الوطني للضمان الاجتماعي مجلس إدارة تعيين after:2026-07-15` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D8%B5%D9%86%D8%AF%D9%88%D9%82%20%D8%A7%D9%84%D9%88%D8%B7%D9%86%D9%8A%20%D9%84%D9%84%D8%B6%D9%85%D8%A7%D9%86%20%D8%A7%D9%84%D8%A7%D8%AC%D8%AA%D9%85%D8%A7%D8%B9%D9%8A%20%D9%85%D8%AC%D9%84%D8%B3%20%D8%A5%D8%AF%D8%A7%D8%B1%D8%A9%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20after%3A2026-07-15&hl=ar&gl=LB&ceid=LB:ar |
| GN-42 | ar | `رئيس مجلس الخدمة المدنية القاضي after:2025-01-01` | https://news.google.com/rss/search?q=%D8%B1%D8%A6%D9%8A%D8%B3%20%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D8%AE%D8%AF%D9%85%D8%A9%20%D8%A7%D9%84%D9%85%D8%AF%D9%86%D9%8A%D8%A9%20%D8%A7%D9%84%D9%82%D8%A7%D8%B6%D9%8A%20after%3A2025-01-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-43 | ar | `هيئة الشراء العام رئيس تعيين` | https://news.google.com/rss/search?q=%D9%87%D9%8A%D8%A6%D8%A9%20%D8%A7%D9%84%D8%B4%D8%B1%D8%A7%D8%A1%20%D8%A7%D9%84%D8%B9%D8%A7%D9%85%20%D8%B1%D8%A6%D9%8A%D8%B3%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86&hl=ar&gl=LB&ceid=LB:ar |
| GN-44 | ar | `مؤسسة كهرباء لبنان مجلس إدارة تعيين` | https://news.google.com/rss/search?q=%D9%85%D8%A4%D8%B3%D8%B3%D8%A9%20%D9%83%D9%87%D8%B1%D8%A8%D8%A7%D8%A1%20%D9%84%D8%A8%D9%86%D8%A7%D9%86%20%D9%85%D8%AC%D9%84%D8%B3%20%D8%A5%D8%AF%D8%A7%D8%B1%D8%A9%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86&hl=ar&gl=LB&ceid=LB:ar |
| GN-45 | ar | `المجلس الأعلى للخصخصة المركز التربوي تعيين after:2026-09-15` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D8%A3%D8%B9%D9%84%D9%89%20%D9%84%D9%84%D8%AE%D8%B5%D8%AE%D8%B5%D8%A9%20%D8%A7%D9%84%D9%85%D8%B1%D9%83%D8%B2%20%D8%A7%D9%84%D8%AA%D8%B1%D8%A8%D9%88%D9%8A%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20after%3A2026-09-15&hl=ar&gl=LB&ceid=LB:ar |
| GN-46 | en | `Lebanon Middle East Airlines chairman Hout` | https://news.google.com/rss/search?q=Lebanon%20Middle%20East%20Airlines%20chairman%20Hout&hl=en-US&gl=LB&ceid=US:en |
| GN-47 | ar | `الهيئة الوطنية لمكافحة الفساد رئيس القاضي after:2025-01-01` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D9%87%D9%8A%D8%A6%D8%A9%20%D8%A7%D9%84%D9%88%D8%B7%D9%86%D9%8A%D8%A9%20%D9%84%D9%85%D9%83%D8%A7%D9%81%D8%AD%D8%A9%20%D8%A7%D9%84%D9%81%D8%B3%D8%A7%D8%AF%20%D8%B1%D8%A6%D9%8A%D8%B3%20%D8%A7%D9%84%D9%82%D8%A7%D8%B6%D9%8A%20after%3A2025-01-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-48 | ar | `محافظ الشمال تعيين after:2025-05-01` | https://news.google.com/rss/search?q=%D9%85%D8%AD%D8%A7%D9%81%D8%B8%20%D8%A7%D9%84%D8%B4%D9%85%D8%A7%D9%84%20%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20after%3A2025-05-01&hl=ar&gl=LB&ceid=LB:ar |
| GN-49 | ar | `التعيينات الإدارية مجلس الوزراء 2026` | https://news.google.com/rss/search?q=%D8%A7%D9%84%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%D8%A7%D8%AA%20%D8%A7%D9%84%D8%A5%D8%AF%D8%A7%D8%B1%D9%8A%D8%A9%20%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1%202026&hl=ar&gl=LB&ceid=LB:ar |
| GN-50 | ar | `تعيين أعضاء المجلس الدستوري مجلس الوزراء` | https://news.google.com/rss/search?q=%D8%AA%D8%B9%D9%8A%D9%8A%D9%86%20%D8%A3%D8%B9%D8%B6%D8%A7%D8%A1%20%D8%A7%D9%84%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D8%AF%D8%B3%D8%AA%D9%88%D8%B1%D9%8A%20%D9%85%D8%AC%D9%84%D8%B3%20%D8%A7%D9%84%D9%88%D8%B2%D8%B1%D8%A7%D8%A1&hl=ar&gl=LB&ceid=LB:ar |

---

## 5. Data sources audit (all probes 2026-09-18 with `curl -sk -A "Mozilla/5.0…"`)

Legend for "Feeds": (a) static structure — bodies, positions, current holders; (b) changes feed — dated tenure changes;
(c) portraits. "SSR" = server-rendered HTML that `curl` can parse; "SPA" = JavaScript shell, needs a browser or the
site's API. Licence "none stated" means no licence text was found on the pages fetched (do not assume open).

### 5.1 Official sources

| Source | Probe result (2026-09-18) | Format / language | Scrapeable? | Feeds | Licensing |
|---|---|---|---|---|---|
| **pcm.gov.lb** (Presidency of the Council of Ministers) | `https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=13587` → HTTP 200, `text/html; charset=utf-8`, 108 KB, 0.6 s; `listingandcalendarnew.aspx?pageid=28` (مقررات مجلس الوزراء) → 200, 113 KB. ASP.NET WebForms (`__VIEWSTATE`, jQuery), SSR. Cookies `BNES_*` = bot-mitigation layer; after roughly 70 page requests from one IP the site serves "Validation request … error code : 421, Number of attempts left : 5" (an image CAPTCHA) — in this session **682 of 752** cached pcm responses were that CAPTCHA page (`scratchpad/gz`, `gz2`). The month/year search on pageid=28 is a POST with `__EVENTVALIDATION` (see `scratchpad/pcmdec/scrape.py`, blocked on its first POST) | HTML, Arabic only (no `/english/` links on the ministers page). Sub-sections: ministers + policy statement (13587); cabinet decisions by session (28 → session pages → article pages); "صدر عن الأمين العام"; agenda ("جدول أعمال مجلس الوزراء", e.g. pageid 26787, 26828); vacancy calls for grade-one posts (pageid 26829 "إعلان لتعيين في وظيفة مدير عام الشؤون الإجتماعية"); **Official Gazette digital issues** ("الجريدة الرسمية الرقمية", `subpg.aspx?pageid=11371`, issues listed by year 2022–2025 on the cached page; `landing.aspx?pageid=9`) | Yes for single pages; **not** for crawling — rate-limit to a handful of requests per day per IP and cache aggressively; expect to solve a CAPTCHA manually when blocked; no RSS | (a) ministers; (b) **yes — session decisions and vacancy calls are the primary changes signal**, but at ≤ 1 session page/day; Gazette PDFs give decree numbers | None stated |
| **lp.gov.lb** (Parliament) | `CustomPage.aspx?Id=53` → 301 to extensionless path; `ContentRecordDetails.aspx?Id=36998` → 301 → `ContentRecordDetails?Id=36998` → 200, 202 KB. IIS 10 / ASP.NET, SSR. Many `CustomPage.aspx?Id=N` return HTTP 500 (cached probe of Id 1–70: 28 pages OK, 42 errors) | HTML, Arabic (some English/French pages UNVERIFIED); PDFs for committee lists (garbled fonts, see part 01 §5.3) | Yes, gently; sequential `ContentRecordDetails?Id=N` makes a **poll on the next Id** a workable changes feed (Id 34472 = 21 Oct 2025 committees; 35713 = 9 Mar 2026 extension; 36998 = 16 Sep 2026 confidence); no RSS; blocs page redirects ("Document Moved") | (a) Bureau, committees, MPs, laws passed ("قوانين صدقت في مجلس النواب"); (b) plenary records, elections inside Parliament | None stated |
| **presidency.gov.lb** | `/` → 200 (118 KB); `/media` → 200; `/gdpr` → 200; `/former-directors` → 200; old `/Arabic/Pages/default.aspx` → 404 (site rebuilt). Cloudflare front, SSR HTML, no SPA markers, no RSS | HTML, Arabic UI (site title in English); sections: `/presidency/3` (biography, oath speech, **election minutes**), `/media/news/<id>` sequential (1314–1415 on the home page), `/gdpr` = المديرية العامة لرئاسة الجمهورية (organisation, Decision 20), `/former-directors` = **structured tenure list with decree number/date/status**, `/defense-council`, `/republican-guard`, `/right-to-access-information/form` | Yes; poll `/media/news/<next id>` | (a) President, DG of the Presidency, Higher Defence Council; (b) decrees signed by the President are announced in `/media/news` (e.g. Decree 823 was reported via NNA/Presidency) ; (c) photo galleries under `/media/category/*` | None stated; privacy policy page only |
| **nna-leb.gov.lb** (National News Agency) | `/ar` → 200, 48 KB, `x-powered-by: Next.js`, Cloudflare; page is a React shell ("جارٍ التحميل، الرجاء الانتظار") with the article payload in `self.__next_f.push` chunks (extractable with `scratchpad/nna.py`); `/rss` → returns the HTML app (no RSS found at that path) | Arabic, English, French editions; article pages carry title/description/dates in the RSC payload | Yes with the payload parser; the search page (`البحث`) is also client-rendered; Google News indexes NNA (`site:nna-leb.gov.lb` queries work, e.g. NNA 29–31 Dec 2025 items on ambassadors and CMA oath) | (b) **primary wire for cabinet decisions, oaths and decree signings** (NNA reported Decree 823 with its number) | None stated |
| **legallaw.ul.edu.lb** (Lebanese University legal informatics centre) | `http://legallaw.ul.edu.lb/Law.aspx?lawId=271942` → 200, 54 KB, IIS 8.0, ASP.NET (`__VIEWSTATE`); HTTP only; one probe in this session failed at connect (curl 000) and succeeded on retry — intermittent | HTML, Arabic (some French); `Law.aspx?lawId=`, `LawArticles.aspx?LawTreeSectionID=`, `LawRelatedAttachments.aspx?lawId=`, `ClarificationsNoteDetails.aspx?id=` (annex scans as images); search page 200 KB | Yes, slowly; not a changes feed (indexing lags) | (a) legal basis for every node (law/decree text, Official Gazette issue and date in the header) | Not stated on pages fetched (university service) |
| **cib.gov.lb** (Central Inspection) | `https://www.cib.gov.lb/` → 301 → `http://www.cib.gov.lb/ar` (nginx); content not examined this session | UNVERIFIED | UNVERIFIED | Potentially (a) directory of administrations (UNVERIFIED) | UNVERIFIED |
| **omsar.gov.lb** | 403, Cloudflare "Just a moment…" JavaScript challenge to curl (both in the earlier cached probe and now) | HTML behind challenge | **No** with curl; needs a real browser session | (a) administrative-reform documents, the 2025 appointments-mechanism forms | UNVERIFIED |
| **Official Gazette (الجريدة الرسمية)** | Published by pcm.gov.lb: digital issues page `subpg.aspx?pageid=11371` (years 2022–2025 listed on the cached 13090 page; 2026 issues UNVERIFIED), subscriptions/announcements landing `landing.aspx?pageid=9`; issue PDFs not opened this session (format UNVERIFIED). Issue numbers appear in secondary sources: Law 41/2026 in OG 11 (supplement) of 9/3/2026 (part 01); Law 44/2017 in OG 27 of 17/6/2017 (legallaw header) | PDF per issue (presumed), Arabic | Same CAPTCHA regime as pcm.gov.lb | (b) authoritative decree numbers and dates for the tenure record | Public record; no licence text |
| **cc.gov.lb** (Constitutional Council) | Decisions listed at `https://www.cc.gov.lb/ar/القرارات/...` with PDFs on CloudFront (part 01 §5.4); home page cached (96 KB, SSR) | HTML + PDF, Arabic | Yes | (b) decisions that annul/suspend laws (3 Sep and 10 Sep 2026) | None stated |
| **elections.gov.lb** (Ministry of Interior) | `https://elections.gov.lb/` → 200, 14 KB, ASP.NET, Cloudflare; landing "الانتخابات النيابية اللبنانية - وزارة الداخلية والبلديات" | HTML + PDF (legal texts, 2022 results) | Yes | (a) 128 seats, districts, 2022 winners; no changes feed | None stated |

### 5.2 Open-data and reference sources

| Source | Probe result | Format / language | Scrapeable? | Feeds | Licensing |
|---|---|---|---|---|---|
| **Wikidata** (`query.wikidata.org/sparql?format=json`) | SPARQL OK; `wbsearchentities` OK. Coverage test: P39 with P580 ≥ 2025-01-01 for Lebanese positions returns the President, First Lady, all 24 ministers (each with P1365 "replaces"), the Deputy PM and three foreign ambassadors to Lebanon — **and nothing else**: no BDL governor (no item for Karim Souaid at all), no army commander tenure (item Q133256703 "Rudolph Haikal" exists but without a dated P39), no DGs, judges, regulators (cached `q1.json`, 61 rows) | JSON; labels en/ar/fr | Yes (public API, rate-limited but generous) | (a) for ministers only; (b) no — Wikidata lags press by weeks and covers nothing below cabinet; (c) P18/P373 pointers | CC0 |
| **Wikimedia Commons** (portraits of the cabinet) | Of the 22 cabinet members with Wikidata items queried (`q4.json`): **9 have a P18 image** (Aoun, Salam, Mitri, Salamé, Saddi, Bayrakdarian, Karami Akkary, Raggi — plus one false match); Commons categories exist for 4 (Aoun, Salam, Mitri, Salamé). 13 ministers (Haidar, Bisat, Sayed, Hajjar, Menassa, Issa el-Khoury, Hage, Rasamny, el-Zein, Khazen Lahoud, Hani, Shehadi, Nasreddine) have **no image**; none of the March 2025 security chiefs or 2025–2026 DGs has an item | JPG/PNG with per-file licence | Yes (API) | (c) partial | Per file (CC BY / CC BY-SA / PD); record licence + author per portrait (decision Q13) |
| **Wikipedia (en/ar)** | `action=raw` wikitext works; "Cabinet of Nawaf Salam" last edited 28 Jun 2026; "2025 in Lebanon" records only Aoun, Haykal, Souaid, municipal elections; "2026 in Lebanon" records no appointments; "Ghassan Skaff" records the 13 Dec 2025 death | Wikitext, en/ar/fr | Yes | (a) ministers, MPs (2022 election table); (b) weak | CC BY-SA 4.0 |
| **Google News RSS** (search substitute) | `news.google.com/rss/search?q=…` returns up to ~100 `<item>`s with title, pubDate, encrypted redirect link (decode: fetch the article page for `data-n-a-sg`/`data-n-a-ts`, then POST to `_/DotsSplashUi/data/batchexecute` — `scratchpad/gq.py`); `after:`/`before:`/`site:` operators work | XML, ar/en | Yes | (b) **best available discovery feed** — every appointment in §4 surfaced here within a day, in 6–12 outlets (Elnashra, المركزية, LBCI, MTV Arabic, Lebanon Debate, akhbaralyawm, Al Akhbar, An-Nahar, Kataeb, Janoubia, Lebanon 24, IMLebanon, L'Orient Today) | Headlines only; article text is each outlet's |
| **Gherbal Initiative** (`elgherbal.org`) | 200 but a 921-byte React shell, "مبادرة غربال — منصة البيانات المفتوحة"; data loaded client-side from an API not identified this session | SPA | Not with curl until the API is mapped (UNVERIFIED) | (a) potentially (public-institution data sets) — UNVERIFIED | UNVERIFIED |
| **LCPS** (`lcps-lebanon.org`) | 200, 137 KB, ASP.NET + jQuery, SSR, en/ar; sections: publications, "tracker", data visualisation; newest item 17 Sep 2026 | HTML/PDF | Yes | Analysis and context, not structure | None stated |
| **Legal Agenda** (`legal-agenda.com`) | 403 Cloudflare JS challenge to curl (three probes); indexed by Google News and heavily cited in §4 (State Council decree delays, PPA vacancy, judicial permutations, NMC, LU) | HTML, ar/en/fr | **No** with curl; via Google News RSS or a browser | (b) high-quality dated analysis of judicial/administrative appointments | © Legal Agenda (no open licence found) |
| **Lebanon Support** (`civilsociety-centre.org`) | 301 → `https://www.socialsciences-centre.org/` (renamed Centre for Social Sciences Research & Action); content not examined | UNVERIFIED | UNVERIFIED | Context | UNVERIFIED |
| **LADE** (`lade.org.lb`) | 301 → `/Home.aspx` (ASP.NET, Cloudflare); content not examined | HTML | Probably (SSR) | Election observation reports; (a) 2022 results context | UNVERIFIED |

### 5.3 Which sources feed what

| Need | Primary | Secondary / fallback |
|---|---|---|
| (a) Static structure — bodies, positions, legal basis | legallaw.ul.edu.lb (texts), lp.gov.lb (Bureau, committees, MPs), pcm.gov.lb ministers page, presidency.gov.lb (Presidency organisation, DG), elections.gov.lb (seats) | Wikidata (ministers, Q-ids), Wikipedia tables |
| (b) Changes feed | pcm.gov.lb session decisions (rate-limited) + NNA (Next.js payload) + presidency.gov.lb news ids + lp.gov.lb record ids | Google News RSS (discovery, ar queries), Legal Agenda (judiciary), cc.gov.lb (annulments) |
| (c) Portraits | Wikimedia Commons (9 of 22 ministers) | Official galleries on presidency.gov.lb/pcm.gov.lb — licence not stated, so self-host only with a recorded permission or under quotation with attribution (decision Q13) |

### 5.4 Reliability notes for the sweep

1. **Google News dates lie for re-indexed archives.** In this pass the English editions of mtv.com.lb, arabnews.com and
   en.kataeb.org surfaced 2015–2025 stories with August–September 2026 dates ("Judge Makkieh appointed Cabinet Secretary
   General", "Karim Souaid Appointed as Governor", "The Cabinet appointed Judge Marwan Abboud as Beirut governor",
   "Lebanese foreign minister quits…", "Defense Minister Appoints Acting Army Commander", "Parliament postpones municipal
   elections for a second time"). Rule for the sweep: an English item is a candidate only when an Arabic item from a
   different outlet exists within ±3 days.
2. **pcm.gov.lb CAPTCHA** (error 421) triggers after tens of requests; the daily sweep should fetch at most the listing
   page plus the newest session page, with a persistent cookie jar, and fall back to NNA.
3. **NNA is a Next.js app**: parse `self.__next_f` chunks, not the DOM.
4. **Cloudflare challenges** block curl on omsar.gov.lb and legal-agenda.com; use Google News RSS for Legal Agenda
   headlines and a headless browser for OMSAR.
5. **Decree numbers** are almost never in press; they must be back-filled from the Official Gazette PDFs on pcm.gov.lb
   or from NNA's wording ("مرسوم رقم 823 تاريخ 5 آب 2025").

---

## 6. Implications for the civicleb model

### 6.1 Size of the changes feed since 1 January 2025

Counting one tenure event per (position, person, date) from §1–§4 (verified rows only, ambassadors counted as one batch,
boards counted once per body):

| Category | Events | Notes |
|---|---|---|
| Constitutional top (President, PM designation, government, Deputy PM, renewed confidence) | 6 | incl. 24 ministerial tenures opened on 2025-02-08 (24 more if each minister is a row — they are) |
| Ministers (24 tenures opened 2025-02-08, 0 closed) | 24 | none closed as of 2026-09-18 |
| Security chiefs and army (incl. acting commander, Information Branch, one intelligence office) | 8 | 13 Mar 2025 wave + Jan and Mar/Aug 2025 |
| Central bank and financial regulators (governor, 4 vice-governors, BCC, CMA, financial prosecutor) | 8 | Mar–Oct 2025 |
| Judiciary and control bodies (PG ×2, State Council president + chambers, HJC ×2 batches, Judicial Council, Decree 823, Court of Audit ×2, Central Inspection, Judicial Inspection, CSB, PPA) | 14 | Decree 823 alone moves ~524 judge tenures — out of v1 scope (judges are not nodes; courts are) |
| Directors general and heads of service (Finance, Industry, Customs ×2, Civil Defence, Health, Social Affairs, Oil, Transport, Higher Education, Consumer Markets, RHUH, Ogero ×2, Industry heads of service batch) | 15 | |
| Boards and regulators (CDR ×2, ERA ×2, TRA, civil aviation, Tele Liban, Port of Beirut, IDAL, Casino, NSSF ×2, EDL, ESEC, Museums, Food Safety, Supervisory Commission, Competition Council, HCP SG, CRDP, Beirut airport, water establishments, employees' cooperative, CNRS) | 24 | |
| Governors | 1 removal (+ 1 UNVERIFIED successor) | |
| Ambassadors | 1 batch decree + ≥6 individually dated postings | Washington, Paris, Riyadh, Damascus, Warsaw, Seoul, Nicosia, Tokyo, Holy See |
| Parliament (extension law, Skaff vacancy, committee chairs Oct 2025, Supervisory Commission, confidence vote) | 5 (+16 committee-chair tenures) | |
| **Total tenure events the feed would already hold** | **≈ 105 verified rows, ≈ 125 with committee chairs; ≈ 150 once UNVERIFIED names and member lists are resolved** | Roughly 6 events per month, clustering on cabinet days (Thursdays at Baabda/Serail) |

### 6.2 Where the daily sweep should look first (in order)

1. **pcm.gov.lb — "مقررات مجلس الوزراء" listing** (`listingandcalendarnew.aspx?pageid=28`): one GET per day; when a new
   session appears, fetch it once; parse "تعيين … / عيّن … / وضع بتصرف / تثبيت / تكليف" sentences. Hard cap of ~5 requests/day.
2. **NNA Arabic** (`nna-leb.gov.lb/ar`, RSC payload): the same evening, Morcos's statement and the presidential decree
   signings ("وقّع مرسوم … رقم …"). This is where decree numbers first appear.
3. **presidency.gov.lb `/media/news/<id+1>`** and **lp.gov.lb `ContentRecordDetails?Id=<id+1>`**: cheap sequential polls;
   the Parliament side yields committee elections, confidence votes, laws passed; the Presidency side yields oaths and decrees.
4. **Google News RSS** (Arabic, ~20 fixed queries from §4.4, plus one per body name) as discovery and corroboration; apply
   the ±3-day Arabic-corroboration rule from §5.4.
5. **cc.gov.lb decisions** weekly (annulments change the legal basis of tenures, e.g. the LU presidency).
6. **Official Gazette PDFs** monthly, to back-fill `instrument_number`/`gazette_issue` on rows created from press.
7. Wikidata is a consumer of our data, not a source, below cabinet level (§5.2).

### 6.3 Schema fields the timeline forces

| Field | Why (evidence from §4) |
|---|---|
| `tenure.status` ∈ {acting (بالإنابة/بالوكالة), assigned (بالتكليف), substantive (بالأصالة), elected_by_board, expired_mandate_continuing, disposal (وضع بتصرف)} | Maarawi, Hajjar, Khreich, al-Khatib were acting then confirmed; Constitutional Council, NMC, Intra continue on expired mandates; Nohra was "placed at the disposal" of the minister — neither dismissed nor replaced |
| `tenure.decision_date` (cabinet/board decision) **and** `tenure.instrument_date` (decree signature) **and** `tenure.effective_date` (oath/assumption) | PPA and CSB: cabinet decision (date unknown) → oath 10 Sep 2026; Decree 823: PM signature 1 Aug, President 5 Aug 2025; Casino/NSSF: cabinet appoints members, board elects chair |
| `instrument.type` ∈ {law, decree, cabinet_decision, ministerial_decision, board_election, parliament_vote, court_decision}, `instrument.number`, `instrument.date`, `instrument.signatories[]` | Decrees 52/53, 823, 2438, 2591, Law 41/2026, CC Decision 7/2026; ministerial decisions (Ogero dismissal, Industry heads of service); Finance Minister's 3-month refusal to countersign the State Council decree is itself a dated fact |
| `instrument.gazette_issue`, `gazette_date`, `gazette_url` | OG 11 (supplement) 9/3/2026; OG 27 17/6/2017; to be back-filled for all 2025–2026 decrees |
| `tenure.predecessor_tenure_id` and `vacancy` records (a tenure of nobody, with `vacancy.reason` ∈ {expired, death, resignation, removal, never_constituted}) | Presidency 2022-10-31 → 2025-01-09; Skaff seat since 2025-12-13; PPA 2026-07-09 → 2026-09-10; ERA/TRA "never constituted" 2002–2025; Syria embassy vacant 4 years |
| `source[]` per tenure with `source.kind` ∈ {official_page, gazette, nna, press_headline, wikidata}, `source.url`, `source.fetched_at`, `source.reliability_note` | Every row above has 1–4 sources; press headlines must be flagged as such and the Google-date caveat recorded |
| `person.names` {ar, en, fr, transliteration variants} | "Souaid/Sweid/Soueid/سعيد/سويد" (two different people: Karim Souaid, BDL; Mazen Soueid, BCC); "Shaaito/Cheaito"; "Qabrasli"; "Jamal" with a wrong first name in one headline |
| `position.confessional_allocation` with `basis` (decision Q8) plus `position.grade` (grade one, etc.) and `position.appointing_authority` (from part 01 §3.1) | Grade-one vacancy statistics (62/149) and the 2025 mechanism only make sense against the grade attribute |
| `position.body_status` ∈ {active, expired_board, paper_only, transitioning} | EDL board "منتهية الولاية" until 17 Sep 2026; Ogero → Liban Telecom; Competition Council constituted 10 Sep 2026 from a 2022 law |
| `review.state` ∈ {draft, reviewed, published} and `review.verification` ∈ {verified, unverified_name, unverified_date} | Q28; most §4 rows have at least one UNVERIFIED cell |

### 6.4 Practical consequences

- Model **acting** tenures as first-class rows: about a fifth of the 2025–2026 events are confirmations of people already in
  post, which change the tenure's status, not its holder.
- Model **boards** as a single node with a dated member list plus a separate chair tenure; the chair often appears weeks after the
  members (NSSF: 6 Aug → 17 Aug 2026).
- Keep **vacancy** as an explicit state so the graph can show the Skaff seat, the PPA gap and the Constitutional Council's expired mandate.
- Expect **≈ 6 candidate events per month**; the Filament review queue (Q12/Q14) will mostly be dedup of 6–12 headlines per event.
- The Presidency's `former-directors` page is the target shape for every position page in civicleb: holder, from, to,
  instrument number, instrument date, employment status — one row per instrument.

Open items to resolve before publishing §4 rows (all marked UNVERIFIED above): Cabinet SG appointment decree; Parliament SG
in 2026; PPA president's name; Osama Mneimneh's appointment date; the successor North governor; the four HJC members of
April 2025; the June 2025 ambassador list; first names of the two new BDL vice-governors; Constitutional Council renewal;
decree numbers for the 13 Mar 2025 security appointments and the 27 Mar 2025 BDL appointment.
