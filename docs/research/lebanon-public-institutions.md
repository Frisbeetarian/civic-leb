# Lebanon's public institutions: research synthesis for civicleb

Research date: 2026-09-18. Four parts follow this synthesis, each written against primary
sources where reachable and marking every unconfirmed claim UNVERIFIED (about 250 flagged
items in total, listed at the end of each part). Sources that were unreachable during the
session: pcm.gov.lb (CAPTCHA after roughly 70 requests), legallaw.ul.edu.lb (HTTP only,
intermittent), statecouncil.gov.lb (expired certificate), csb.gov.lb legal pages (403),
omsar.gov.lb and legal-agenda.com (Cloudflare challenge).

| Part | Covers | Size |
|---|---|---|
| 01 | Constitutional bodies, legal classification of public bodies, appointment mechanics, confessional allocation, Parliament (128-seat table, committees, 2026 extension) | 325 lines |
| 02 | Cabinet, 22 ministries and their DGs, attached public institutions, regulators, PCM councils and funds, state-owned companies, counts | 599 lines |
| 03 | Ordinary, administrative, exceptional and confessional courts; oversight bodies; army and security services | 182 lines |
| 04 | Officeholders as of 2026-09-18, timeline of about 105 appointments since January 2025, vacancies, audit of 15 data sources | 395 lines |

## A. Facts that change the picture

1. **No election in 2026.** Parliament's term was extended to 31 May 2028 by Law 41 of 9 March 2026 (76 votes), upheld by Constitutional Council Decision 7/2026 on 7 April 2026. The seat roster is the 2022 chamber, 127 of 128 filled (Ghassan Skaff, West Bekaa, died 13 December 2025; no by-election). (Part 01 §5.4, Part 04 §3)
2. **The Salam government is in office with full powers**, unchanged since 8 February 2025, with a renewed confidence vote on 16 September 2026 (68/12/2). (Part 04 §2)
3. **The judicial reform was annulled.** Judicial Organisation Law 36 of 5 January 2026 was struck down in full by Constitutional Council Decision 1/2026 on 25 February 2026; Legislative Decree 150/1983 governs the Higher Judicial Council. (Part 03 §1)
4. **Lebanon has nine governorates, not eight**: Keserwan-Jbeil was created by Law 52/2017. (Part 01 §2.1)
5. **Six expatriate seats exist on paper.** Law 44/2017 Article 112 creates six diaspora seats (134-seat chamber), suspended for 2022 only by Law 8/2021; absent a new amendment they revive at the next election. (Part 01 §5.2)
6. **Several bodies sit on expired or non-existent mandates**: the Constitutional Council bench (expired 2025, still deciding), the National Media Council, Intra's board; the Ombudsman (Law 664/2005) was never appointed; the Electricity Regulatory Authority stayed unconstituted from 2002 until September 2025; the Ministry of Technology and AI law passed Parliament in July 2026 but promulgation is unverified. (Parts 02 §2, 03 §4, 04 §4)
7. **Acting officeholders are the norm.** The Ministry of Finance's 2026 org chart shows 15 of 20 directors acting; 62 of 149 grade-one posts were vacant in December 2025; about a fifth of 2025-2026 tenure events are confirmations of people already in post. (Part 02 §5, Part 04 §6)
8. **Node count**: the executive alone is about 200 organisations plus 140 head positions; with Parliament (128 seats, 16 committees, blocs), the judiciary (about 95 nodes including 19 confessional courts) and the constitutional core, v1 lands at 600 to 650 nodes. Judges are not nodes; courts are. (Part 02 §4, Part 03 §6)
9. **Changes feed baseline**: about 105 verified tenure events since 1 January 2025, about 150 once names are resolved, roughly 6 per month clustering on cabinet days. (Part 04 §6.1)

## B. Recommended changes to `docs/decisions.md`

### B1. Node taxonomy (Q29) — extend the subtypes

Keep CivLab's six types. Add subtypes and one attribute:

| Add | For | Source |
|---|---|---|
| `state_company` (with `ownership: {ownerNodeId, share}`) | MEA, Intra, Casino du Liban, touch, Alfa, Télé Liban, Beirut Airport SAL, Housing Bank, Port of Beirut SAL | 01 §2.2, 02 §5 |
| `oversight` (on `commission`) | Civil Service Board, Central Inspection, Higher Disciplinary Board, Court of Audit (dual), NACC, Supervisory Commission for Elections, NHRC, Constitutional Council | 02 §5, 03 §6 |
| `council` (on `commission`) | Higher Defence Council, Higher Council for Privatisation, ministerial committees chaired by President or PM | 02 §5 |
| `directorate_general` (on `department`) | Only for DGs that have attached bodies or public salience (Civil Aviation, Oil, Customs); otherwise the DG is a `dept_head` position on the ministry | 01 §2.1, 02 §5 |
| `territorial_unit` (phase 2) | 9 governorates, 26 districts | 01 §2.1 |
| `legalForm` attribute, separate from `subtype` | `ministry, directorate_general, public_institution, autonomous_service, independent_authority, central_bank, company, temporary_committee, local_authority` (Ogero is legally a public institution but behaves as an operator; the Port of Beirut committee has no legal personality) | 01 §2.2, 02 §5 |

Boards: model each public institution's board as a `commission` child node with a dated member list and a separate chair tenure; the DG is a `dept_head`. Chair and DG may coincide as one person holding two positions. (01 §6.7, 04 §6.4)

### B2. Edge vocabulary — add Lebanon-specific relations

CivLab's nine edge types cannot express the most common executive relation (tutelage) or the control relations. Recommended additions, v1 first:

| Edge | Direction | v1? | Source |
|---|---|---|---|
| `tutelage` (وصاية) with legal source | ministry or PCM → public institution | yes | 01 §2.2, 02 §5 |
| `owns` with `share` | state or BdL → company | yes | 02 §5 |
| `inspects` | Central Inspection, Judicial Inspection, Inspector General → bodies | yes | 03 §6 |
| `prosecutes` | Public Prosecution → courts; Government Commissioner → Military Court | yes | 03 §6 |
| `reviews` with `outcome` | Constitutional Council → laws; State Council → administrative acts; Cassation → confessional courts | yes | 03 §6 |
| `commands` | President → LAF (Art. 49), distinct from Minister of Defence's administrative link | yes | 03 §6 |
| `regulates` | regulator → sector entities | later (sector entities are mostly outside the graph) | 02 §5 |
| `disciplines`, `refers_to` | disciplinary boards → officials; Cabinet → Judicial Council | later, or fold into `inspects` with `power` | 03 §6 |

Keep `oversees` for parliamentary and audit oversight, with a `kind` attribute (`audit, parliamentary, civil_service, financial_control, prudential`). Keep hierarchy (minister → DG → directorate) in `parent`/`children`, as CivLab does, not as an edge. `appoints` gets a structured payload: instrument type, proposer, signatories, majority, advisory bodies. The source of an `appoints` edge is the deciding authority (Council of Ministers for all grade-one posts), never the signatories. (01 §6.3)

### B3. Status vocabulary — richer than filled/acting/caretaker/vacant/never_constituted

Body status: `active, never_constituted, dormant, expired_continuing, transitioning, ad_hoc, dissolved`.
Tenure status: `substantive (بالأصالة), acting (بالإنابة), assigned (بالتكليف), caretaker, elected_by_board, expired_continuing, disposal (وضع بتصرف)`, plus `endReason: term, resignation, dismissal, death, retirement, extension, annulment`.
Vacancies are explicit records (a tenure of nobody) with `reason: expired, death, resignation, removal, never_constituted`. (01 §6.5, 03 §6.5, 04 §6.3)

### B4. Legal sources must be versioned

The annulment of Law 36/2026 shows a node's legal basis must point to a version of a text with `inForce`, `annulledBy` and `supersededBy`. Instrument types: `constitution, taif, law, legislative_decree, decree, decree_in_com, cabinet_decision, ministerial_decision, board_election, parliament_vote, court_decision, rules_of_procedure, custom`. Each instrument carries number, date, signatories, gazette issue, gazette date and URL. (01 §6.9, 03 §6.6, 04 §6.3)

### B5. Tenures need three dates and per-source provenance

`decision_date` (cabinet or board decision), `instrument_date` (decree signature), `effective_date` (oath or assumption). Example: the Public Procurement Authority was decided in cabinet, then sworn in on 10 September 2026; Decree 823/2025 was signed by the PM on 1 August and the President on 5 August. Every tenure carries `source[]` with kind (`official_page, gazette, nna, press_headline, wikidata`), URL, fetched-at and a reliability note. (04 §6.3)

### B6. Positions carry grade, appointing authority and confession basis

`grade: one | one_equivalent | null`, `appointingAuthority` (node id), `confession`, `confessionBasis: constitution | pact | custom`, `confessionSource` URL. Only the 128 seats are `constitution` (Art. 24 and Law 44/2017 Annex 1); only President, PM, Speaker, Deputy Speaker and Deputy PM are `pact`; every other allocation (army commander, BdL governor, security DGs, grade-one posts) is `custom` and must cite its press or academic source. (01 §4, §6.2)

### B7. Parliament seats (Q10)

128 seat nodes keyed by major district, minor district, confession and ordinal, from the reconciled Annex 1 table in Part 01 §5.2. Add the six Article 112 expatriate seats as `never_constituted` seat nodes flagged suspended, so the 134-seat contingency is representable. Blocs are dated memberships, not positions. (01 §5.5, §6.10)

### B8. Layout (Q9) holds, with placement rules

Four sectors stand. Oversight bodies attached to the PCM go in Independent & Regulatory under an "Oversight and control" band with their `appoints` edges drawn back to the Council of Ministers. The Court of Audit sits on the Judicial/Independent boundary with `functions: [audit, jurisdiction]`. Security services are an inner ring of the Executive sector, not a fifth sector, because every chief is appointed by the Council of Ministers. Confessional courts belong in the graph: six state-organised Sharia and Druze court systems in the Judicial sector proper, thirteen recognised community tribunals on its outer edge with `stateFunded: false`. (03 §6.1 to §6.3)

### B9. Persons need name variants

`person.names {ar, en, fr, variants[]}`. Two different officials transliterate as "Souaid/Soueid" (Karim Souaid at BdL, Mazen Soueid at the Banking Control Commission). (04 §6.3)

### B10. Sweep design (Q7) — revise sources and rate limits

Order of the daily sweep: (1) pcm.gov.lb session-decision PDFs ("مقررات جلسة … .pdf"), one GET per day for the listing and one per new session, hard cap of about 5 requests per day because the site raises a CAPTCHA after roughly 70 requests; (2) NNA Arabic, which is a Next.js app whose content sits in the `__next_f` payload, where decree numbers first appear; (3) presidency.gov.lb and lp.gov.lb sequential record IDs polled cheaply; (4) Google News RSS with about 20 fixed Arabic queries, applying a ±3-day corroboration rule because re-indexed old articles carry fake dates; (5) cc.gov.lb decisions weekly; (6) Official Gazette PDFs monthly to back-fill instrument numbers. legallaw.ul.edu.lb is HTTP-only and intermittent, so creating-text lookups should be a batch job with retries, not part of the daily sweep. (04 §5, §6.2)

### B11. Portraits (Q13) — Commons is thin

Wikimedia Commons has portraits for 9 of 22 ministers and Wikidata has no item for the BdL governor. Portrait sourcing needs official ministry and body pages as a second source, with licence recorded per image; expect many positions to launch without a portrait. (04 §5)

### B12. Scope (Q2, Q17) — confirmed, with two adjustments

The whole national state is realistic at 600 to 650 nodes. Two adjustments: judges are never nodes (Decree 823/2025 moved about 524 judges), and public hospitals should be one class node in v1 rather than 29 nodes. The 60-node first milestone should include at least one body in each new subtype and one instance of each new status so the schema is exercised end to end. (02 §4, 03 §6.7)

## C. Verification debt before publishing

Each part ends with an UNVERIFIED list. The highest-value items: decree numbers for the 13 March 2025 security appointments and the 27 March 2025 BdL appointment; the four BdL vice-governors; the Cabinet Secretary General's appointment decree; the current Parliament Secretary General; the Constitutional Council renewal; the successor North governor; whether the Ministry of Technology and AI law was promulgated; creating-text numbers for about 30 public institutions; the confessional allocation of the six governorships and several security posts. The Official Gazette and legallaw.ul.edu.lb are the places to close these, in a dedicated pass once the schema exists so results land directly as draft rows.

---


# 01 — Constitutional and legal framework of the Lebanese state

Research part for civicleb. Written 2026-09-18. All officeholder and status claims are dated in the text; treat anything undated as "as of 2026-09-18".

**Method.** Primary texts were read directly where reachable: the Parliament's official English PDF of the Constitution (lp.gov.lb), the Constitutional Council's own PDF of Law 250/1993 and of Decision 7/2026 (cc.gov.lb), Legislative Decrees 111/112/114/115 of 1959 as published by Central Inspection (cib.gov.lb), Decree 4517/1972 and Decree-Law 150/1983 and Law 44/2017 with its Annex 1 on legallaw.ul.edu.lb, the National Defence Law pages of the Presidency and the Army, the Parliament's Rules of Procedure and committee lists on lp.gov.lb. Several official sites were unreachable during the session (pcm.gov.lb TLS error / CAPTCHA, statecouncil.gov.lb expired certificate, csb.gov.lb legal pages 403, presidency.gov.lb news archive 404, legallaw intermittently refusing connections). Where a claim rests on a secondary source (Wikipedia, Al-Dawliya lil-Maalumat / The Monthly, Legal Agenda, press) it is labelled **(secondary)**. Anything not confirmed is marked **UNVERIFIED**. The three sub-reports this part synthesises (admin-law classification and appointments; confessional allocation; Parliament) were produced by parallel research agents against the same rules.

Legend used in tables: **P** = primary source read; **S** = secondary source; **U** = UNVERIFIED.

---

## 1. Constitutional bodies and their creating texts

Constitution text: promulgated 23 May 1926, last amended by the Constitutional Law of 21 September 1990 (Taif). Official English PDF: https://lp.gov.lb/backoffice/uploads/files/Lebanese%20%20Constitution-%20En.pdf (P). Arabic PDF: https://www.lp.gov.lb/backoffice/uploads/files/%D8%A7%D9%84%D8%AF%D8%B3%D8%AA%D9%88%D8%B1%20%D8%A7%D9%84%D9%84%D8%A8%D9%86%D8%A7%D9%86%D9%8A(1).pdf. Article-by-article Arabic on the Lebanese University database: http://www.legallaw.ul.edu.lb/LawView.aspx?LawID=244058&opt=view (Art. 65 direct: http://www.legallaw.ul.edu.lb/LawArticles.aspx?LawArticleID=1007504&LawId=244058). Presidency mirror of the Constitution and Taif: https://presidency.gov.lb/lebanon-system/3 and https://presidency.gov.lb/lebanon-system/4.

### 1.1 Summary table

| Body (ar) | Creating / organising text | Key provisions | Composition and how filled | Src |
|---|---|---|---|---|
| President of the Republic (رئيس الجمهورية) | Constitution Arts. 49–63 | Art. 49: head of state, "shall preside over the Supreme Defense Council and be the Commander-in-Chief of the Armed Forces which fall under the authority of the Council of Ministers"; elected by Parliament by secret ballot, 2/3 on first ballot, absolute majority thereafter, 6-year term, not immediately re-eligible. Art. 53: designates the PM after binding parliamentary consultations (53.2), alone issues the designation decree (53.3), issues with the PM the cabinet-formation decree (53.4). Art. 54: all presidential decisions countersigned by PM and minister(s) concerned except the PM-designation and government-resignation decrees. Art. 56: promulgates laws, issues decrees, may ask the CoM to reconsider within 15 days. Art. 60: liable only for violation of the Constitution or high treason, impeached by 2/3 of all members, tried by the Supreme Council (Art. 80). Art. 62: on vacancy the CoM exercises presidential powers by delegation. | Elected by Parliament (Art. 49). Joseph Aoun elected 9 January 2025 (ends the vacuum since 31 Oct 2022) — https://en.wikipedia.org/wiki/2025_in_Lebanon (S) | P |
| Council of Ministers (مجلس الوزراء) | Constitution Arts. 17, 64–69; internal regulation "تنظيم أعمال مجلس الوزراء" (decree, amended by Decree 4717 of 31/1/1994 and Decree 8550 of 29/8/2002) | Art. 17: executive power entrusted to the CoM. Art. 65: sets general policy, drafts bills and regulatory decrees, "shall watch over the execution of laws and regulations and supervise the activities of all the Government's branches including the civil, military, and security administration and institutions", "shall appoint state employees dismiss them and accept their resignation according to the law"; quorum 2/3; decisions by consensus else majority of those present; **basic issues need 2/3 of the members named in the formation decree**, listed exhaustively: constitutional amendment, state of emergency, war and peace, general mobilisation, international agreements, annual budget, long-term development plans, **appointment of grade-one employees and equivalents**, administrative divisions, dissolution of the Chamber, electoral law, nationality law, personal-status laws, dismissal of ministers. Art. 66: ministers administer the state services; collective responsibility before the Chamber. Art. 69: cases in which the government is deemed resigned; a minister is dismissed by decree signed by President and PM after 2/3 of the CoM. Internal regulation: two-thirds quorum, consensus else open vote, decisions minuted by the Secretariat General within five days, competent minister must sign the implementing decree drafts — https://presidency.gov.lb/lebanon-system/5 (the original decree number of the regulation is not shown on that page: **U**). | Formed by decree of President + PM (Art. 53.4/64.2); must win confidence within 30 days (Art. 64.2). Nawaf Salam government: formed 8 Feb 2025, confidence 26 Feb 2025 with 95/128 (S: https://en.wikipedia.org/wiki/Cabinet_of_Nawaf_Salam); **renewed confidence 16 Sep 2026, 68 for / 12 against / 2 abstentions, after a policy-statement debate** — https://www.lp.gov.lb/ContentRecordDetails.aspx?Id=36998 (P). 24 ministers. | P |
| Prime Minister (رئيس مجلس الوزراء) | Constitution Art. 64 | Head of government; ex officio Deputy Head of the Supreme Defence Council (64.1); conducts consultations to form the government and signs the formation decree with the President (64.2); "shall sign with the President of the Republic all decrees" except his own designation and the resignation decree (64.4); convenes the CoM and sets its agenda (64.6); follows up the work of administrations and public institutions (64.7). | Designated by the President after binding consultations (Art. 53.2–3). Nawaf Salam designated 13 Jan 2025 (S: Wikipedia 2025 in Lebanon). | P |
| Parliament / Chamber of Deputies (مجلس النواب) | Constitution Arts. 16–47; Electoral Law 44 of 17/6/2017; Rules of Procedure of 18/10/1994 as amended (OG 52, 13/11/2003) | Art. 16: legislative power in a single chamber. Art. 24: seats split equally Christian/Muslim, proportionally among sects and regions, until a non-confessional law. Art. 32: two ordinary sessions (from the first Tuesday after 15 March to end May; from the first Tuesday after 15 October to year-end). Art. 42: elections within the 60 days before expiry. Art. 43: adopts its own rules. Art. 44: elects Speaker and Deputy Speaker for the full term. Term of 4 years is statutory: Law 44/2017 Art. 1 — http://legallaw.ul.edu.lb/LawArticles.aspx?LawTreeSectionID=284492&LawID=271942&language=ar. Rules: https://www.lp.gov.lb/CustomPage.aspx?Id=7 | 128 members elected 15 May 2022; mandate extended to 31/5/2028 by Law 41 of 9/3/2026 (see §5). | P |
| Speaker (رئيس مجلس النواب) and Deputy Speaker | Constitution Art. 44; Rules Arts. 1–8 | Elected separately, secret ballot, absolute majority of votes cast (relative majority at the third ballot, tie → oldest), for the Chamber's term; confidence may be withdrawn once, two years after election, by 2/3 on a petition of ≥10 deputies. Bureau = Speaker, Deputy Speaker, two secretaries, three commissioners (Rules Art. 1). | Nabih Berri (Speaker) and Elias Bou Saab (Deputy) elected 31 May 2022, still in office — https://www.lp.gov.lb/CustomPage.aspx?Id=53 (P) | P |
| Constitutional Council (المجلس الدستوري) | Constitution Art. 19 (introduced 21/9/1990); Law 250 of 14/7/1993, amended by Laws 305/1994, 150 of 30/10/1999, 650 of 4/2/2005, the law of 9/6/2006 (repealed) and 43 of 3/11/2008; internal rules Law 243/2000 | Art. 19: supervises the constitutionality of laws and rules on parliamentary and presidential election disputes; referral by the President, Speaker, PM, any ten deputies, and (for personal status, freedom of belief and religious education) the recognised heads of communities. Law 250 new Art. 1: "المجلس الدستوري هيئة دستورية مستقلة ذات صفة قضائية". New Art. 2: ten members, "يعين مجلس النواب نصف هؤلاء الاعضاء بالاكثرية المطلقة ... في الدورة الاولى وبالاكثرية النسبية ... في الدورة الثانية" and "يعين مجلس الوزراء النصف الآخر بأكثرية ثلثي عدد اعضاء الحكومة". New Art. 3 (Law 43/2008): all ten from honorary judges (25 years judicial, administrative or financial), law/political-science professors (25 years) or lawyers (25 years); Lebanese ≥10 years, aged 50–74; candidacy by declaration filed with the Council's registry. New Art. 4: six-year term, non-renewable; replacement by the same appointing authority for the remainder. Official PDF: https://d3eoo4vpw0ewa2.cloudfront.net/documents/loi_ar_0.pdf (linked from https://www.cc.gov.lb/ar/الدستور-والقوانين/قانون-انشاء-المجلس/). Taif had also given the Council the power to *interpret* the Constitution; Law 250 omitted it — https://www.lebarmy.gov.lb/en/node/26069 (S). | Current members (cc.gov.lb, undated page, read 2026-09-18): President Tannous Mechleb, Vice-President Omar Hamza, SG Awni Ramadan, members Albert Serhan, Michel Tarazi, Mireille Najm, Elias Mechrekani, Riad Abou Ghida, Ahmad Akram Baassiri, Fawzi Farhat — https://www.cc.gov.lb/ar/المجلس/أعضاء-المجلس/ (P). Decision 7/2026 of 7/4/2026 bears the same names (P). | P |
| Higher Judicial Council (مجلس القضاء الأعلى) | Constitution Art. 20 (judicial independence, judgments in the name of the Lebanese people); Legislative Decree 150 of 16/9/1983 "قانون القضاء العدلي" (OG 45, 10/11/1983), Art. 2 as amended by Leg. Decree 22/1985, Law 389/2001 and Law 327/2024; **Judicial Organisation Law 36 of 5/1/2026** (see §3.3) | Art. 2 (150/1983 as amended): ten members — three ex officio (First President of the Court of Cassation, president; Public Prosecutor at Cassation, vice-president; President of Judicial Inspection), **two elected** by the First President, chamber presidents and counsellors of Cassation from among Cassation chamber presidents (3 years), **five appointed by decree on the proposal of the Minister of Justice** for three years non-renewable (one Cassation chamber president, two Appeal chamber presidents, one first-instance president, one judge from the Ministry's units). Art. 4: supervises the good functioning, dignity and independence of the judiciary. Art. 5: drafts the judicial appointments and transfers (التشكيلات القضائية), which "لا تصبح نافذة إلا بعد موافقة وزير العدل"; on persistent disagreement the HJC decides by 7 votes, binding; issued "بمرسوم بناء على اقتراح وزير العدل". Text: http://www.legallaw.ul.edu.lb/LawView.aspx?opt=view&LawID=194133 (P, read by the appointments agent). | The chair ex officio = First President of Cassation. Holder as of 2026-09-18: **U** (not verified this session; see Part 03). | P |
| State Council / Shura (مجلس شورى الدولة) | Legislative Decree 10434 of 14/6/1975 (نظام مجلس شورى الدولة), amended notably by Law 227 of 31/5/2000 which re-created first-instance administrative courts | "يتألف القضاء الاداري بمقتضى النظام الحالي لمجلس شورى الدولة، من مجلس شورى الدولة ومن محاكم ادارية"; composition: president, Government Commissioner, chamber presidents and counsellors, six chambers (one administrative/advisory, five judicial); Bureau of the Council (president, Government Commissioner as vice-president, chamber presidents, presidents of the highest administrative courts); non-binding opinions on draft laws, decrees and treaties; members appointed by decree in the CoM on the proposal of the Minister of Justice — https://www.justice.gov.lb/index.php/court-details/20/2 (P, Ministry of Justice summary). Article numbers for the president's appointment: **U** (statecouncil.gov.lb certificate expired; legallaw refused connections). | Attached to the Ministry of Justice for administrative purposes (S). Holder: see Part 03. | P/U |
| Court of Audit (ديوان المحاسبة) | Legislative Decree 82 of 16/9/1983 (تنظيم ديوان المحاسبة, OG 39, 29/9/1983); PDF listed by the Public Procurement Authority: https://www.ppa.gov.lb/ar/pages/details/105 (download URL required login) | "ديوان المحاسبة محكمة ادارية تتولى القضاء المالي" and "يرتبط ديوان المحاسبة اداريا برئيس مجلس الوزراء" — https://www.coa.gov.lb/ar/عن-الديوان (P). Decree 4517/1972 Art. 26 and 31 make it the a-posteriori auditor of public institutions and its president chairs their annual audit committee (P: http://www.legallaw.ul.edu.lb/LawView.aspx?opt=view&LawID=243846). Composition (president, public prosecutor at the Court, chamber presidents, counsellors) and the appointment article: **U** (decree text not retrievable this session). | President (as displayed on coa.gov.lb, read 2026-09-18): Judge Mohammad Badran — https://www.coa.gov.lb/ (P) | P/U |
| Judicial Council (المجلس العدلي) | Code of Criminal Procedure, Law 328 of 7/8/2001, Arts. 355–366 (as amended by Law 711 of 9/12/2005) | Art. 358: chaired by the First President of Cassation with four Cassation judges "appointed by decree on the proposal of the Minister of Justice after approval of the HJC"; cases are referred only "بناءً على مرسوم يُتخذ في مجلس الوزراء"; since 2005 its judgments admit objection and retrial (Art. 366). Source: https://lebarmy.gov.lb/ar/content/المجلس-العدلي-في-القانون-اللبناني (official Army journal article, treat as S for article numbers). | Ad hoc composition per decree. | S |
| Supreme Council for the Trial of Presidents and Ministers (المجلس الأعلى لمحاكمة الرؤساء والوزراء) | Constitution Arts. 60, 70, 71, 80; Law 13 of 18/8/1990 on procedure before the Supreme Council | Art. 70: the Chamber may impeach the PM and ministers for high treason or breach of duties by 2/3 of all members; Art. 71: tried by the Supreme Council; Art. 80: seven deputies elected by the Chamber and eight of the highest-ranking judges by seniority, presided by the highest judge; conviction by ten votes; prosecutor = a judge appointed by the Court of Cassation in plenary (Art. 60) (P, Constitution PDF). Never issued a conviction; "born dead" — https://aawsat.com/home/article/1943246/ (S). | The Chamber elected its seven deputy members in the sitting of 26 July 2022 — https://www.lp.gov.lb/ContentRecordDetails?Id=31554 (P). Names: **U**. Judicial members: **U**. | P/S |
| Economic and Social Council (المجلس الاقتصادي والاجتماعي, now "والبيئي") | Taif Agreement (calls for its creation); Law 389 of 12/1/1995 and amendments | Advisory body attached to the Presidency of the CoM; gives opinions on economic and social matters referred by the PM or self-seized by 2/3 of its members (Art. 3); opinions "ليس ملزما"; 71 members representing sectors, professional bodies, unions, experts, emigrants — https://cese.gov.lb/institution/ar (P). A decree approved by the cabinet in March 2026 appointed a new 80-member council, ~78% new faces, 18.75% women — https://nhrclb.org/archives/5792 (S; decree number **U**). The law renaming it to include "Environmental": **U**. | Members by decree in the CoM (S). President elected by the members (S). | P/S |
| Higher Defence Council (المجلس الأعلى للدفاع) | Constitution Arts. 49 and 64.1; National Defence Law, Legislative Decree 102 of 16/9/1983, Arts. 7–9 | President of the Republic (chair), PM (vice-chair), Ministers of Defence, Foreign Affairs, Finance, Interior and Economy; other ministers may be added by decree in the CoM; Secretary General = an officer of colonel rank or above appointed by decree in the CoM on the proposal of the PM and Minister of Defence; convened by its chair or one-third of members; decisions confidential — https://presidency.gov.lb/defense-council (P). National Defence Law text: https://www.mod.gov.lb/sites/default/files/2019-08/قانون-الدفاع-الوطني.pdf | Chair ex officio = President; vice-chair ex officio = PM. | P |
| Central Inspection (التفتيش المركزي) | Legislative Decree 115 of 12/6/1959 | Art. 1: "أنشئ لدى رئاسة الوزارة تفتيش مركزي تشمل صلاحياته جميع الإدارات العمومية والمؤسسات العامة والمصالح المستقلة والبلديات"; judiciary, army, ISF and General Security subject only in the financial field. Art. 3: Inspection Board = head of Central Inspection (president), head of Research and Guidance, most senior Inspector General. Art. 5: the head "يعين بمرسوم يتخذ في مجلس الوزراء" (age ≥40, ≥15 years' service incl. 5 in grades one/two, no elective political office in the last five years); inspectors general by decree in the CoM on the head's proposal. Art. 19: imposes disciplinary sanctions, refers to disciplinary councils, the Court of Audit and the public prosecutor — https://www.cib.gov.lb/ar/إنشاء-التفتيش-المركزي (P); PDF copy https://archive.marsadtaif.com/node/714 (P). | Head appointed by decree in the CoM. Holder: see Part 03. | P |
| Civil Service Board (مجلس الخدمة المدنية) | Legislative Decree 114 of 12/6/1959; organisation Decree 8337/1961 | Art. 1: jurisdiction over all public administrations, public institutions and municipalities, excluding "القضاء والجيش ... وقوى الأمن الداخلي والأمن العام وأمن الدولة"; Art. 2: powers over "تعيين الموظفين وترقيتهم وتعويضاتهم ونقلهم وتأديبهم وصرفهم"; Art. 6: president appointed by decree in the CoM on the PM's proposal (age ≥40, ≥15 years' service incl. 5 in grade one); Art. 7: members by decree; Art. 9: CSB approval a prerequisite for personnel transactions — https://www.cib.gov.lb/ar/إنشاء-مجلس-الخدمة-المدنية (P, read by the appointments agent); https://archive.unescwa.org/civil-service-board (S). Structure: Board, Personnel Administration (Monitoring & Studies; Competitions & Personal Files), Research and Guidance — https://www.csb.gov.lb/ar/هيكلية-المجلس/ (P). | President (as displayed on csb.gov.lb, read 2026-09-18): Nisrine Machmouchi — https://www.csb.gov.lb/ar/ (P). | P |
| Disciplinary councils / Higher Disciplinary Board (مجالس التأديب / الهيئة العليا للتأديب) | Legislative Decree 112 of 12/6/1959 (نظام الموظفين), Art. 57 | Three disciplinary councils; the one for grade one and two employees and senior inspectors "يتألف ... من هيئة مجلس الخدمة المدنية ومن قاض من الفئة الثانية على الأقل تنتدبه وزارة العدلية" plus a peer official, with the head of Central Inspection as government representative — https://www.cib.gov.lb/ar/node/2005 (P). The body known today as **الهيئة العليا للتأديب** (a judge-chaired board attached to the Presidency of the CoM) is not named in the 1959 text as published there; its creating amendment: **U**. | Chair: a judge (S). Holder: **U**. | P/U |
| Central Administration of Statistics (إدارة الإحصاء المركزي) | Decree 1793/1979 (creation and functions); Decree 2728/1980 (structure, 256 posts) | An administration under the Presidency of the Council of Ministers — cas.gov.lb "administrative information" page (returned 404 during the session; figures from its search snippet: **S/U**); home page confirms "Presidency of the Council of Ministers – Central Administration of Statistics" — https://www.cas.gov.lb/ (P). | Head: Maria Nalbandian, **Acting** Director General — https://www.oicstatcom.org/databases-nso-detail.php?c_code=32 (S, undated). | S/U |

### 1.2 Notes on the constitutional text that matter for modelling

- **Presidential vacancy.** Art. 62: the CoM exercises presidential powers by delegation. This is why a `status: vacant` on the President node must propagate an `ex_officio`-like delegation edge to the Council of Ministers node (2022–2025 precedent).
- **Caretaker.** Art. 64.2: a resigned government acts only "in the narrow sense of a care-taker government" (تصريف الأعمال). `status: caretaker` on the Council of Ministers node and on every minister tenure is a first-class state, not an annotation.
- **Countersignature chain.** Arts. 54 and 64.4 mean that almost every decree carries three signatures (President, PM, competent minister) but the *deciding* authority for grade-one and equivalent appointments is the Council of Ministers acting by 2/3 (Art. 65.5). See §3 for the modelling consequence.
- **Preamble (j).** "There shall be no constitutional legitimacy for any authority which contradicts the pact of mutual existence" (official English lettering is **J**, not (i)) — the textual hook for the National Pact conventions in §4.
- **Art. 22 (Senate)** and the **Art. 95 National Committee** for abolishing political confessionalism are constitutional bodies that have never been constituted. Both are good `never_constituted` nodes.

---

## 2. How Lebanese administrative law classifies public bodies

There is no single code of administrative organisation. The categories below are those actually used in the texts, with the governing instrument for each. Full detail (articles, examples, sources) is in the appointments agent's report, summarised here.

### 2.1 Categories, governing texts, proposed civicleb mapping

| Legal category (ar / en) | Governing text | Defining features | Examples | Proposed civicleb type / subtype | Src |
|---|---|---|---|---|---|
| الإدارات العامة — central administration: ministries and directorates general | Legislative Decree 111 of 12/6/1959 "تنظيم الإدارات العامة" (Art. 2: ministry = one or more directorates general → directorates → services → departments; Art. 7: the DG is the hierarchical head under the minister); staffing Decree 2894/1959, Decree 15712/2005 — https://www.cib.gov.lb/ar/node/1906 | No legal personality; the minister is the authority; DG posts are grade one | All ~22 ministries and their DGs; Directorate General of Civil Aviation (Beirut airport is run by a DG of the Ministry of Public Works, S: https://www.maharat-news.com/aviationauthoritylebanon); Customs; Grain and Sugar-Beet Office (**U** as to form) | `department` / `ministry`; DG = `dept_head` (subtype `director_general`, grade one) | P |
| وظائف الفئة الأولى وما يعادلها — grade-one posts and equivalents | Legislative Decree 112 of 12/6/1959 Art. 3 (categories → ranks → steps) and **Art. 12: "يعين موظفو الفئة الاولى بمرسوم يتخذ في مجلس الوزراء"** — https://www.cib.gov.lb/ar/node/2005; Constitution Arts. 65.5 and 95(b) | Not a body type but a *position class* that cuts across categories (DGs, governors, heads of public institutions, security DGs). ~236 such posts, 134 vacant in Feb 2025 (S: https://www.imlebanon.org/2025/02/14/gov-leb-20/) | — | Position attribute `grade: "one"` / `gradeOneEquivalent: true` on `dept_head` positions | P |
| المؤسسات العامة — public institutions | **Decree 4517 of 13/12/1972 "النظام العام للمؤسسات العامة"** (OG 100, 14/12/1972, 50 articles) — http://www.legallaw.ul.edu.lb/LawView.aspx?opt=view&LawID=243846 | Art. 2: a public-law person "تتولى مرفقا عاما وتتمتع بالشخصية المعنوية والاستقلالين المالي والاداري"; Art. 3: created by decree in the CoM which fixes its *type* and attaches it to a ministry exercising **administrative tutelage (الوصاية الإدارية)**; Art. 4: board of directors + DG; Art. 5: board of 3–7 appointed "بمرسوم يتخذ في مجلس الوزراء بناء على اقتراح سلطة الوصاية وبعد استطلاع رأي مجلس الخدمة المدنية", 3-year renewable terms (Art. 6); Art. 13: DG appointed the same way; Arts. 21–24: tutelage approvals and a Government Commissioner named by the tutelage minister; Arts. 26–31: subject to CSB, Central Inspection, MoF financial controller and a-posteriori Court of Audit control. Types (administrative vs industrial/commercial; "ذات طابع خاص" exempted by their own law) are fixed in each creating text, not enumerated in 4517: **U** as a general definition. | EDL (Decree 16878/1964, industrial-commercial, S); CDR (Leg. Decree 5/1977, attached directly to the CoM, S); Litani River Authority (law of 14/8/1954, S); Ogero (Law 21/1972: a هيئة, not a company, S); IDAL (Law 360/2001, tutelage = Presidency of the CoM, P: https://investinlebanon.gov.lb/en/about_idal); water establishments (Law 221/2000, **U**); Lebanese University; CNRS (**U**); Council of the South (attached to the PCM, S); Régie (**U** as to form) | `department` / `public_institution`; board = `commission`-like sub-node or `administers` edges; chair and DG = `dept_head`; tutelage minister → `oversees` (kind = tutelage) | P |
| المصالح المستقلة — autonomous services (legacy) | Named in the 1972 enabling law and in Leg. Decree 115/1959 Art. 1 | Older form with financial autonomy but weaker personality; several were converted into public institutions | Régie des Tabacs (historically), pre-2000 water offices (**U** which survive) | `department` / `public_institution` with `legalForm: "autonomous_service"` | P/U |
| الهيئات المستقلة / الناظمة — independent and regulatory authorities | No general statute; each created by its own law, typically with an express exemption from Decree 4517 (e.g. Law 431/2002 Art. 4: "لا تخضع هذه الهيئة لأحكام النظام العام للمؤسسات العامة") — https://www.tra.gov.lb/Library/Files/Uploaded%20files/Law431/Law-431.htm | Legal personality, administrative and financial independence; members by decree in the CoM on the sector minister's proposal, fixed non-renewable terms | TRA (Law 431/2002; board reconstituted 2025); Electricity Regulatory Authority (Law 462/2002; members first appointed Sept 2025, S); Civil Aviation Authority (Law 481/2002; board July 2025, S); Capital Markets Authority (Law 161/2011; BDL Governor chairs ex officio, P: https://www.cma.gov.lb/en/about-cma); National Anti-Corruption Commission (Law 175/2020); Public Procurement Authority (Law 244/2021); Supervisory Commission for Elections (Law 44/2017); National Audiovisual Council (Law 382/1994); National Human Rights Commission (Law 62/2016). Compositions of the last five: **U** this session. | `commission` / `regulator` (independent ring) | P/S |
| مصرف لبنان — the central bank | Code of Money and Credit, Decree 13513 of 1/8/1963; Art. 18 Governor by decree in the CoM on the Finance Minister's proposal, 6-year renewable; four vice-governors by decree in the CoM, 5 years (S: https://en.wikipedia.org/wiki/Banque_du_Liban) | "legal public entity enjoying financial and administrative autonomy ... not subject to the administrative and management rules and controls applicable to the public sector" (S) | BDL; Banking Control Commission (Law 28/67, **U**) | `department` / `central_bank`; BCC = `commission` / `regulator` | S |
| الشركات المملوكة من الدولة والشركات المختلطة — state-owned and mixed companies | Ordinary company law; treated by the CoM as a category distinct from public institutions and regulators (decision of 17 Sept 2026 asking OMSAR to study board allowances across "المؤسسات والهيئات الناظمة والشركات المملوكة من الدولة والمؤسسات العامة", S: pcm.gov.lb session page as read by the appointments agent, path **U**) | Private-law form, public ownership, often via BDL | MEA SAL (BDL ~99%); Intra Investment Co.; Casino du Liban (majority IIC); Alfa / touch (state-owned mobile operators); the Beirut airport operating company approved 16 June 2026 (S) | `department` / **new subtype `state_company`** (see §6) | S |
| الامتيازات والهيئات المؤقتة — concessions and temporary committees | Ad hoc | Port of Beirut: state-owned since 1990, run by a Temporary Committee that "operates outside any legislative framework" (S: https://maharat-news.com/portbeirutmanagement) | Port of Beirut Temporary Committee (**U** founding decision) | `department` / `public_institution` with `legalForm: "temporary_committee"` and `status` flag | S |
| البلديات واتحادات البلديات — municipalities and unions | Decree-Law 118 of 30/6/1977 (Municipal Act): municipality = "إدارة محلية" with legal personality and autonomy; council elected, president elected by the council; in Beirut the governor is the executive authority; unions created by decree, union council = member presidents — https://www.baladiyat.org/?page_id=6633 (S mirror). Mukhtars: law of 27/11/1947 amended by Law 665 of 29/12/1997, 6-year term (S) | Local self-government under three-tier control (qaimaqam, muhafiz, Minister of Interior) | ~1,000 municipalities, elections held 4 May 2025 | `constituency` (local electorate) → `elects` → `elected` (municipal council / president); sector `local` (phase 2) | S |
| المحافظات والأقضية — deconcentrated administration | Decree-Law 116 of 12/6/1959 "التنظيم الإداري"; Law 522 of 16/7/2003 (Akkar, Baalbek-Hermel); **Law 52 of 7/9/2017 (Keserwan-Jbeil)** | **Nine** governorates today (not eight: Beirut, Mount Lebanon, North, Akkar, Bekaa, Baalbek-Hermel, South, Nabatieh, Keserwan-Jbeil) and 26 districts; governors are grade-one officials appointed by decree in the CoM — https://en.wikipedia.org/wiki/Governorates_of_Lebanon (S); article numbers in 116/1959: **U** | Governors (muhafiz), qaimaqams | `department` / **new subtype `territorial_unit`** with `dept_head` governor; sector `local` | S/U |
| المرافق العامة — public utilities | Functional term in Decree 4517 Art. 2, not an organisational category | — | — | none (do not model as a type) | P |

### 2.2 Categories the current taxonomy cannot express cleanly

1. **Tutelage (الوصاية الإدارية).** The single most common structural relation in the executive (minister ↔ public institution) is neither `appoints` nor `dept_head`; `oversees` is the closest but is also used for parliamentary and audit oversight. Recommend a `kind` attribute on `oversees` (`tutelage | audit | inspection | parliamentary | financial_control`).
2. **Boards of directors.** A public institution has a *board* (deliberative) and a *DG* (executive) that are distinct positions with different appointment paths and, since the 17 Sept 2026 EDL decision, can be distinct people. CivLab's `dept_head` fits the DG; the board needs either a `commission` child node with `administers` → institution, or board-seat positions.
3. **State-owned companies** and **territorial units (governorates/districts)** have no subtype; both should be added (§6).
4. **Position class "grade one"** is orthogonal to node type and must be a position attribute.
5. **Legal form vs. function**: Ogero is legally a public institution but functions as an operator; MEA is a company but state-controlled through BDL. Keep `legalForm` separate from `subtype`.

---

## 3. Appointment mechanics

### 3.1 Master table: who appoints whom, under which instrument

| Post / office | Deciding authority | Instrument | Proposer / prior step | Signatories | Majority | Legal basis | Edge mapping | Src |
|---|---|---|---|---|---|---|---|---|
| President of the Republic | Parliament | election, secret ballot | — | — | 2/3 first ballot, absolute majority after | Const. Art. 49 | `Parliament —elects→ President` | P |
| Speaker, Deputy Speaker, Bureau | Parliament | election | — | — | absolute then relative | Const. Art. 44; Rules Arts. 1–3 | `Parliament —elects→ Speaker` | P |
| Prime Minister | President | decree of the President alone | binding parliamentary consultations, results disclosed to the Speaker | President only | — | Const. Art. 53.2–3, 54 | `President —appoints→ PM` (instrument = presidential decree); `Parliament —advises→ President` (consultations) | P |
| Ministers (cabinet formation) | President + PM | decree | PM's consultations | President, PM | — | Const. Arts. 53.4, 64.2 | `President —appoints→ Minister` with `coSignatory: PM`; then `Parliament —confirms→ CouncilOfMinisters` (vote of confidence, Art. 64.2) — **the only genuine `confirms` edge in the system** | P |
| Dismissal of a minister | CoM 2/3, decree of President + PM | decree | — | President, PM | 2/3 of CoM | Const. Art. 69.2 | tenure end reason `dismissed` | P |
| Grade-one civil servants (DGs, governors, heads of CI/CSB, etc.) | Council of Ministers | **decree taken in the CoM** (مرسوم يتخذ في مجلس الوزراء) | competent minister's proposal; CSB shortlist of three under the 2025 mechanism | President, PM, competent minister | **2/3 of the CoM** (Art. 65.5) | Leg. Decree 112/1959 Art. 12; Const. Art. 65.3 and 65.5; Art. 95(b) parity | `CouncilOfMinisters —appoints→ Post` with metadata `{instrument:"decree_in_com", proposer, signatories, majority:"2/3", csbShortlist:true}` | P |
| Boards and DGs of public institutions | Council of Ministers | decree in the CoM | tutelage authority's proposal after CSB opinion | President, PM, tutelage minister | 2/3 (equivalents of grade one) | Decree 4517/1972 Arts. 5, 13 | same pattern; add `Minister —advises→ CoM` (proposal) and `CSB —advises→ CoM` if the pipeline is to be visible | P |
| Government Commissioner at a public institution | Tutelage minister | ministerial decision | — | minister | — | Decree 4517 Art. 24 | `Minister —appoints→ GovernmentCommissioner` (instrument = decision) | P |
| Members of regulators (TRA, ERA, CAA, etc.) | Council of Ministers | decree in the CoM | sector minister's proposal | President, PM, minister | 2/3 | e.g. Law 431/2002 Art. 6 (5-year non-renewable) | `CoM —appoints→ RegulatorSeat` | P |
| BDL Governor; four vice-governors | Council of Ministers | decree in the CoM | Minister of Finance (vice-governors: after consulting the Governor) | President, PM, MoF | 2/3 | Code of Money and Credit Art. 18 (Governor 6 yrs; VGs 5 yrs); dismissal Art. 19 (S). Article split 18/19 **U** | `CoM —appoints→ BDLGovernor`; Karim Souaid appointed 27 March 2025 (S) | S |
| Army Commander; Chief of Staff | Council of Ministers | decree in the CoM | Minister of National Defence (CoS: after consulting the Commander) | President, PM, MoD | 2/3 | National Defence Law, Leg. Decree 102/1983 — https://www.lebarmy.gov.lb/ar/content/قيادة-الجيش (P); Military Council Art. 26 (P: https://www.lebarmy.gov.lb/ar/content/المجلس-العسكري-في-وزارة-الدفاع-الوطني-اللبناني) | `CoM —appoints→ ArmyCommander`; Rodolphe Haykal appointed 13 March 2025 (S) | P |
| DG Internal Security Forces | Council of Ministers | decree in the CoM | Minister of Interior | President, PM, MoI | 2/3 | Law 17 of 6/9/1990 (article **U**) | same | S/U |
| DG General Security | Council of Ministers | decree in the CoM | Minister of Interior | President, PM, MoI | 2/3 | Leg. Decree 139 of 12/6/1959 (article **U**) | same | S/U |
| DG State Security | Council of Ministers | decree in the CoM | (arm of the Higher Defence Council) | President, PM | 2/3 | Defence Law Art. 7 §5 as amended by Leg. Decrees 1/1984 and 39 of 23/3/1985 (S: ar.wikipedia citing state-security.gov.lb) | same | S |
| Higher Council of Customs; DG Customs | Council of Ministers | decree in the CoM | Minister of Finance | President, PM, MoF | 2/3 | 1950 decree-law / Customs Law 2000 (S) | same | S |
| Secretary General of the Higher Defence Council | Council of Ministers | decree in the CoM | PM + Minister of Defence | President, PM, MoD | — | Leg. Decree 102/1983 Arts. 7–9 — https://presidency.gov.lb/defense-council | same | P |
| Head of Central Inspection; inspectors general | Council of Ministers | decree in the CoM | (head: none stated; IGs: head's proposal) | President, PM | 2/3 | Leg. Decree 115/1959 Art. 5 | same | P |
| President and members of the CSB | Council of Ministers | decree in the CoM | PM | President, PM | 2/3 | Leg. Decree 114/1959 Arts. 6–7 | same | P |
| Grade two, three, four | Government | decree (not "in the CoM") after CSB approval | minister | President, PM, minister | — | Leg. Decree 112/1959 Arts. 9, 11 | `Minister —appoints→` with `instrument: "decree"`; **below grade one is out of v1 scope** | P |
| Contract and wage staff | Minister | ministerial decision | — | minister | — | Decree 5883/1994 (S) | out of scope | S |
| Higher Judicial Council seats | (a) ex officio ×3; (b) Cassation judges elect ×2; (c) decree ×5 | decree on the Minister of Justice's proposal | MoJ | President, PM, MoJ | — | Leg. Decree 150/1983 Art. 2 | (a) `Post —ex_officio→ HJC`; (b) `CassationJudges —elects→ HJCSeat`; (c) `MinisterOfJustice —appoints→ HJCSeat` with `instrument: decree, signatories` | P |
| Judicial appointments and transfers (التشكيلات القضائية) | HJC drafts → MoJ approves (or HJC overrides by 7/10) → decree | decree on the MoJ's proposal | HJC | President, PM, MoJ | HJC 7/10 on disagreement | Leg. Decree 150/1983 Art. 5 | `HJC —advises→ MoJ` (draft) + `MoJ —confirms→ Transfers` + `President —appoints→ Judge` with `proposer: MoJ`; genuinely multi-stage (see §6) | P |
| Judicial Council members | decree | decree on the MoJ's proposal after HJC approval | MoJ, HJC | President, PM, MoJ | — | CCP Art. 358 (S) | `MoJ —appoints→ JudicialCouncilSeat` with `approver: HJC` | S |
| State Council and Court of Audit presidents and members | Council of Ministers | decree in the CoM | Minister of Justice (State Council); PM for the Court of Audit (**U**) | President, PM, minister | 2/3 | Leg. Decree 10434/1975; Leg. Decree 82/1983 (articles **U**) | `CoM —appoints→` | P/U |
| Constitutional Council | Parliament (5) and Council of Ministers (5) | parliamentary election; decree in the CoM | candidacy declarations filed with the Council | — / President, PM | absolute→relative majority (Parliament); **2/3 of the CoM** | Law 250/1993 new Arts. 2–4 | `Parliament —elects→ CCSeat ×5`; `CoM —appoints→ CCSeat ×5` with `majority: "2/3"` | P |
| Supreme Council for the Trial of Presidents and Ministers | Parliament (7 deputies); seniority (8 judges) | election; ex officio by rank | — | — | — | Const. Art. 80; Law 13/1990 | `Parliament —elects→ SupremeCouncilSeat ×7`; `Judge —ex_officio→ SupremeCouncil ×8` | P |
| Economic, Social and Environmental Council members | Council of Ministers | decree in the CoM | representative bodies (decree defining the "most representative" bodies) | President, PM | — | Law 389/1995 (S) | `CoM —appoints→ ESCSeat`; `ESC —advises→ CoM` | S |
| Municipal councils; mukhtars | local electorate | election | — | — | — | Decree-Law 118/1977; Law 665/1997 | `LocalElectorate —elects→ MunicipalCouncil`; `MunicipalCouncil —elects→ MunicipalPresident` | S |

### 3.2 The 2025 "appointments mechanism" (آلية التعيينات)

- Adopted by the **Council of Ministers on 20 March 2025** as a cabinet *decision* (not a law or decree), "بصيغتها النهائية" — https://www.naharnet.com/stories/en/311760-govt-approves-appointments-mechanism-salam-emphasizes-on-competency (S); https://today.lorientlejour.com/article/1452643/salam-government-adopts-skills-based-mechanism-for-appointments.html (S). Decision number and full text: **U** (pcm.gov.lb unreachable).
- Reported content (S: Kataeb.org, IMLebanon 21 March 2025): nine principles (priority, competition, transparency, inclusiveness, conflict-of-interest avoidance, flexibility, participation, diversity, accountability); Art. 95 parity in grade one **without reserving posts to sects**; candidates from inside the administration (grade two encouraged) and outside; the competent minister proposes in coordination with the Minister of State for Administrative Development; an interview committee keeps minutes; **the CSB proposes three names per vacancy and the CoM picks one and issues the decree**. Whether a public call for candidates is mandatory: **U**.
- Earlier mechanisms (2010 Hariri cabinet decision; 2015): **U** this session. The 2020 episode in which President Michel Aoun referred to the Constitutional Council a law "in force de jure" fixing an appointment mechanism for grade one (presidency.gov.lb news nid=26034, page now 404) confirms that Parliament once legislated a mechanism and the presidency contested it; the law number and the Council's decision: **U**.
- No 2025–2026 *law* on the appointment mechanism was found (2025 and 2026 legislative timelines silent): **U / likely none**.
- Practical consequence observed in 2025: first regulator boards ever appointed (ERA, CAA, Cannabis Authority, TRA re-launch), first judicial appointments since 2017 (August 2025, S: HRW World Report 2026 https://www.hrw.org/world-report/2026/country-chapters/lebanon).

### 3.3 Judicial independence / Judicial Organisation Law — status

- Parliament passed a judicial independence law on 31 July 2025; President Aoun returned it on 5 September 2025; Parliament adopted an amended version on 18 December 2025 (S: https://www.hrw.org/news/2025/08/15/lebanon-judicial-reforms-positive-but-fall-short; HRW World Report 2026).
- The Constitutional Council lists **Decision 1/2026 of 25/2/2026 on "Judicial Organisation Law No. 36 of 5/1/2026"** (قانون التنظيم القضائي), challenged by two groups of deputies (reviews 1/2026 and 3/2026) — https://www.cc.gov.lb/ar/القرارات/قرارات-دستورية-القوانين/ page 2 (P). This is the promulgated form of the amended judicial law. **Outcome of Decision 1/2026 (rejection, partial or full annulment): U** — the decision PDF was not retrieved; must be read before any HJC composition is entered as current (the new law reportedly makes 4 seats elected by judges, 4 ex officio/appointed on HJC nomination, 2 co-opted, 5-year terms — S: HRW; ISPI).
- Other 2026 Council decisions relevant to the graph (P, same listing): 4/2026 (Budget Law 40/2026), 7/2026 (Law 41/2026 term extension, petitions **rejected**, see §5), 8/2026 (Law 45/2026 on Lebanese University councils, annulled), 9/2026 (Law 47/2026 social security, annulled), 10/2026 (Law 70/2026 general amnesty, suspended, 10/9/2026).

### 3.4 Where the CivLab vocabulary is insufficient

1. **Multi-actor decrees.** "Decree taken in the Council of Ministers on the proposal of minister X, signed by the President, the PM and X, by 2/3" is the normal appointment path for ~236 posts. One `appoints` edge from the deciding body (Council of Ministers) with `proposer`, `signatories`, `majority`, `instrument` attributes is the recommended encoding; drawing separate `appoints` edges from President, PM and minister would misstate who decides.
2. **Advisory pipeline steps** (CSB shortlist, HJC draft, State Council opinion, tutelage approval) need `advises` edges with a `stage` attribute or an intermediate event node; CivLab's `advises` is undirected as to stage.
3. **`confirms`** is needed exactly once (vote of confidence) plus the Minister of Justice's approval of judicial transfers; keep it narrow.
4. **Ex officio seats by rank** (the eight most senior judges on the Supreme Council; the "highest-ranking judge" presiding) cannot be tied to a named post; they need `ex_officio` with `rule: "seniority"`.
5. **Two appointing authorities for one body** (Constitutional Council 5+5; HJC 3+2+5): fine as multiple edges, but the seat nodes must carry `appointedBy` so the pill renders correctly.

---

## 4. Confessional allocation

Three legal bases must be distinguished and stored on each position as `confessionBasis: constitution | pact | custom`.

### 4.1 Constitution (text)

- **Nothing in the Constitution names a sect for any post.** All post-to-sect mappings are pact or custom.
- **Art. 24**: seats equal between Christians and Muslims, proportional among sects within each community and among regions, "until such time as the Chamber enacts an electoral law on a non-confessional basis" (P).
- **Art. 95** (as amended 1990): the Chamber elected on parity shall abolish political confessionalism by a transitional plan under a National Committee chaired by the President; during the transition "(a) The sectarian groups shall be represented in a just and equitable manner in the formation of the Cabinet. (b) The principle of confessional representation in public service jobs, in the judiciary, in the military and security institutions, and in public and mixed agencies shall be cancelled ... However, Grade One posts and their equivalents shall be excepted from this rule, and the posts shall be distributed equally between Christians and Muslims without reserving any particular job for any sectarian group" (P). Note that the widely repeated claim that Art. 95 requires "an equal number of Muslim and Christian ministers" is not the text; parity in cabinet is convention.
- **Art. 22**: a Senate representing all communities once a non-confessional Chamber is elected (never constituted). **Art. 19**: recognised heads of communities may refer personal-status laws. **Art. 9–10**: freedom of conscience and communities' schools. **Preamble (j)**: no legitimacy for an authority contradicting the pact of coexistence (P).
- **Art. 65.5**: grade-one appointments are a "basic issue" needing 2/3 (P).

### 4.2 Taif Agreement (22 Oct 1989; constitutionalised 21 Sept 1990)

Text: https://presidency.gov.lb/lebanon-system/4 (P, Arabic) and https://peacemaker.un.org/sites/default/files/document/files/2024/05/lb891022taif20accords.pdf. Operative clauses: Chamber seats "بالتساوي بين المسيحيين والمسلمين، نسبياً بين طوائف كل من الفئتين، نسبياً بين المناطق"; Speaker and Deputy Speaker "ينتخب رئيس المجلس ونائبه لمدة ولاية المجلس"; abolition of confessional representation in public posts except grade one, which are "مناصفة بين المسيحيين والمسلمين دون تخصيص أية وظيفة لأية طائفة"; creation of the Constitutional Council, the Economic and Social Council and the Supreme Council for trying presidents and ministers; expanded administrative decentralisation at district level. Taif names no sect for any office and does **not** contain the "Finance = Shia" rule sometimes attributed to it.

### 4.3 National Pact and custom — table

Sources: The Monthly / Al-Dawliya lil-Maalumat surveys of grade-one posts by sect (July 2017: https://monthlymagazine.com/ar-article-desc_4419_; judiciary June 2010: https://monthlymagazine.com/cms/upload/magazine/Issue%2095%20-%20June%202010.pdf), IMLebanon vacancy lists by sect (14 and 23 Feb 2025: https://www.imlebanon.org/2025/02/14/gov-leb-20/, https://www.imlebanon.org/2025/02/23/government-lebanon-228/), General Security's DG list (https://www.general-security.gov.lb/en/about/directors), Wikipedia for the pact offices. All are **S**; "since" only where a source states it.

| Post | Confession | Basis | Since / note | Verification |
|---|---|---|---|---|
| President of the Republic | Maronite | pact (1943) | — | V (S) |
| Prime Minister | Sunni | pact | — | V (S) |
| Speaker of Parliament | Shia | pact | — | V (S) |
| Deputy Speaker | Greek Orthodox | pact | — | V (S) |
| Deputy Prime Minister | Greek Orthodox | pact | Tarek Mitri 2025 | V (S) |
| Army Commander | Maronite | custom | — | V (S) |
| Army Chief of Staff | Druze | pact/custom | — | V (S) |
| Military Council (6 seats) | Maronite, Druze, Sunni, Shia, Greek Orthodox, Greek Catholic | custom | — | V (S) |
| DG General Security | Shia (Maronite/Christian 1945–1998) | custom | swap of 21 Dec 1998 (Jamil al-Sayyed) | V (S) |
| DG Internal Security Forces | Sunni | custom | — | V (S) |
| DG State Security | Greek Catholic | custom | since 1998 swap | V (S) |
| Deputy DG State Security | Shia | custom | 2025 appointee | P-level (S) |
| BDL Governor | Maronite | custom | — | V (S) |
| BDL vice-governors 1–4 | Shia, Druze, Sunni, Armenian Orthodox | custom | order of 3rd/4th partly verified | P-level (S) |
| Government Commissioner at BDL | Greek Orthodox | custom | — | P-level (S) |
| Banking Control Commission chair | Sunni | custom | — | P-level (S) |
| First President of Cassation = President of the HJC | Maronite | custom | one exception 1990–92 | V (S) |
| Public Prosecutor at Cassation | Sunni (Maronite before Taif) | custom | post-Taif | V (S) |
| Head of Judicial Inspection | Sunni | custom | — | V (S) |
| President of the State Council | **Maronite** | custom | — | V (S) |
| President of the Court of Audit | Shia (Sunni before Taif) | custom | post-Taif | V (S) |
| Financial Public Prosecutor | Shia | custom | — | P-level (S) |
| Head of the Permanent Military Court | Shia | custom | — | V (S) |
| Constitutional Council | 5 Christians / 5 Muslims | custom (on Law 250) | presidency Maronite in practice | rule **U** |
| Secretary General of the Council of Ministers | **Sunni** | custom | — | V (S) |
| DG of the Presidency of the Republic | Maronite | custom | conflicting 2025 listing | V with conflict (S) |
| Head of Central Inspection | Maronite | custom | — | V (S) |
| President of the Civil Service Board | Sunni | custom | — | V (S) |
| DG Ministry of Finance | **Maronite** | custom | — | V (S) |
| DG Ministry of Justice | Sunni | custom | — | V (S) |
| DG Customs | Maronite | custom | — | P-level (S) |
| DG Public Health | Druze | custom | — | V (S) |
| President of the Lebanese University | Shia | custom | — | V (S) |
| President of CDR | Sunni | custom | — | P-level (S) |
| Governor of Beirut | Greek Orthodox | custom | — | V (S) |
| Governor of the South | Druze | custom | — | P-level (S) |
| Governor of Nabatieh | Shia | custom | — | P-level (S) |
| Governors of Mount Lebanon, North, Bekaa, Baalbek-Hermel, Akkar, Keserwan-Jbeil | — | custom | — | **U** |
| Director of Army Intelligence; SG Higher Defence Council; SG Parliament; DG Foreign Affairs; DG Interior | — | custom | — | **U** |

Corrections to common assumptions surfaced by the sources: the State Council president is Maronite (not Greek Orthodox); the DG of Finance is Maronite (not Shia); the SG of the Council of Ministers is Sunni (not Maronite).

### 4.4 Cabinet conventions

- Christian/Muslim parity in the Council of Ministers is convention resting on Art. 95(a). The Salam government (24 ministers) is 12/12: Maronite 5, Greek Orthodox 3, Greek Catholic 2, Armenian Orthodox 1, Protestant 1; Sunni 5, Shia 5, Druze 2 (S: https://en.wikipedia.org/wiki/Cabinet_of_Nawaf_Salam; portfolio attribution not individually footnoted there).
- Finance = Shia since Feb 2014 (Ali Hassan Khalil → Ghazi Wazni → Youssef Khalil → Yassine Jaber), justified politically as the "third signature" on decrees (the Finance Minister countersigns most financial decrees); Speaker Berri calls it a Taif right, which the Taif text does not support (S: https://today.lorientlejour.com/article/1444764/allocation-of-finance-ministry-to-shiites-is-right-granted-by-taif-berri.html).
- Interior is customarily Sunni; Defence and Foreign Affairs customarily Christian without a fixed sect (S).

### 4.5 Grade one: rule versus practice

Art. 95(b) forbids reserving any grade-one post to a sect; every survey found (Monthly 2017 "157 وظيفة يحتكرها زعماء الطوائف", Al-Akhbar 2017, An-Nahar 2011, IMLebanon 2025 vacancy counts *per sect*) shows posts treated as sect property. The 2025 mechanism restates the constitutional rule; the July 2025 TRA slate was reportedly redone because it failed the sectarian-balance test (S: https://www.imlebanon.org/2025/07/21/aoun-salam5432-2/). For civicleb: store `confession` + `confessionBasis: custom` on the *position*, never on the person, and render the Art. 95(b) text as the legal caveat.

---

## 5. Parliament

### 5.1 Constitutional and statutory frame

See §1.1 (Arts. 16, 24, 32, 42–44) and Law 44/2017 Art. 1 (128 members, four-year term, proportional system, one round). Law text: http://www.legallaw.ul.edu.lb/Law.aspx?lawId=271942 (OG 27, 17/6/2017); government PDF: https://elections.gov.lb (2017 legal texts). Chapter VIII (Arts. 98–99) electoral system; Chapter XI (Arts. 111–126) expatriate voting.

### 5.2 Districts and seats by confession (Law 44/2017, Annex 1)

Art. 2(a): "ويعتبر الجدول جزءاً لا يتجزأ من هذا القانون". Annex 1 scan on legallaw: http://legallaw.ul.edu.lb/ClarificationsNoteDetails.aspx?id=18508&language=ar (P; transcribed by the Parliament agent, totals reconciled; cross-checked against https://en.wikipedia.org/wiki/2022_Lebanese_general_election). 15 major districts (دوائر كبرى), 26 minor districts (دوائر صغرى).

| Major | Minor | Mar | Sun | Shia | GO | GC | Druze | ArmO | ArmC | Evang | Alaw | Min | Total |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| بيروت الأولى | Beirut I (Achrafieh, Rmeil, Medawar, Saifi) | 1 | – | – | 1 | 1 | – | 3 | 1 | – | – | 1 | 8 |
| بيروت الثانية | Beirut II | – | 6 | 2 | 1 | – | 1 | – | – | 1 | – | – | 11 |
| الجنوب الأولى | Saida | – | 2 | – | – | – | – | – | – | – | – | – | 2 |
| الجنوب الأولى | Jezzine | 2 | – | – | – | 1 | – | – | – | – | – | – | 3 |
| الجنوب الثانية | Tyre | – | – | 4 | – | – | – | – | – | – | – | – | 4 |
| الجنوب الثانية | Zahrani | – | – | 2 | – | 1 | – | – | – | – | – | – | 3 |
| الجنوب الثالثة | Bint Jbeil | – | – | 3 | – | – | – | – | – | – | – | – | 3 |
| الجنوب الثالثة | Nabatieh | – | – | 3 | – | – | – | – | – | – | – | – | 3 |
| الجنوب الثالثة | Marjeyoun–Hasbaya | – | 1 | 2 | 1 | – | 1 | – | – | – | – | – | 5 |
| البقاع الأولى | Zahle | 1 | 1 | 1 | 1 | 2 | – | 1 | – | – | – | – | 7 |
| البقاع الثانية | Rachaya–West Bekaa | 1 | 2 | 1 | 1 | – | 1 | – | – | – | – | – | 6 |
| البقاع الثالثة | Baalbek–Hermel | 1 | 2 | 6 | – | 1 | – | – | – | – | – | – | 10 |
| الشمال الأولى | Akkar | 1 | 3 | – | 2 | – | – | – | – | – | 1 | – | 7 |
| الشمال الثانية | Tripoli | 1 | 5 | – | 1 | – | – | – | – | – | 1 | – | 8 |
| الشمال الثانية | Minieh | – | 1 | – | – | – | – | – | – | – | – | – | 1 |
| الشمال الثانية | Denniyeh | – | 2 | – | – | – | – | – | – | – | – | – | 2 |
| الشمال الثالثة | Zgharta | 3 | – | – | – | – | – | – | – | – | – | – | 3 |
| الشمال الثالثة | Bcharre | 2 | – | – | – | – | – | – | – | – | – | – | 2 |
| الشمال الثالثة | Koura | – | – | – | 3 | – | – | – | – | – | – | – | 3 |
| الشمال الثالثة | Batroun | 2 | – | – | – | – | – | – | – | – | – | – | 2 |
| جبل لبنان الأولى | Jbeil | 2 | – | 1 | – | – | – | – | – | – | – | – | 3 |
| جبل لبنان الأولى | Keserwan | 5 | – | – | – | – | – | – | – | – | – | – | 5 |
| جبل لبنان الثانية | Metn | 4 | – | – | 2 | 1 | – | 1 | – | – | – | – | 8 |
| جبل لبنان الثالثة | Baabda | 3 | – | 2 | – | – | 1 | – | – | – | – | – | 6 |
| جبل لبنان الرابعة | Chouf | 3 | 2 | – | – | 1 | 2 | – | – | – | – | – | 8 |
| جبل لبنان الرابعة | Aley | 2 | – | – | 1 | – | 2 | – | – | – | – | – | 5 |
| **Total** | 26 minor / 15 major | **34** | **27** | **27** | **14** | **8** | **8** | **5** | **1** | **1** | **2** | **1** | **128** |

Christians 64 (34+14+8+5+1+1+1), Muslims 64 (27+27+8+2). Major-district totals: Beirut I 8, Beirut II 11, South I 5, South II 7, South III 11, Bekaa I 7, Bekaa II 6, Bekaa III 10, North I 7, North II 11, North III 10, Mount Lebanon I 8, II 8, III 6, IV 13.

**Amendments.** Law 67 of 13/4/2018 (suspended the magnetic card for 2018); Law "in force de jure" No. 8 of 3/11/2021 (passed 28/10/2021, unpromulgated) suspended for the 2022 election only Art. 112 (six expatriate seats: Maronite, Orthodox, Catholic, Sunni, Shia, Druze, one per continent), Art. 118 §1 and Art. 122 §1 (134-seat chamber in the following election) — http://legallaw.ul.edu.lb/Law.aspx?lawId=288099 (P). Absent a new amendment, those provisions revive for the next election; the 2025 push by 68+ MPs to let expatriates vote for all 128 seats was kept off the agenda (S: https://nowlebanon.com/deepening-divide-between-berri-and-parliament-on-expatriates-voting/); outcome of the reported compromise: **U**.

### 5.3 Organs: Bureau, Secretariat, standing committees

Rules of Procedure (النظام الداخلي), 18/10/1994 as amended to 21/10/2003 (OG 52, 13/11/2003): https://www.lp.gov.lb/CustomPage.aspx?Id=7 (P).

- **Bureau** (Art. 1): Speaker, Deputy Speaker, two secretaries, three commissioners. Art. 3: Speaker/Deputy for the term; secretaries re-elected at each October session; commissioners on one ballot by relative majority. Art. 8: Bureau powers (agenda, minutes, vote results). Composition elected 31/5/2022: Berri, Bou Saab, secretaries Alain Aoun and Hadi Abou El Hosn, commissioners Michel Moussa, Abdel Karim Kabbara, Hagop Pakradounian — https://www.lp.gov.lb/CustomPage.aspx?Id=53 (P). Whether secretaries/commissioners were re-elected in October 2025: **U** (page still shows the 2022 slate).
- **Secretariat General**: Art. 23 assigns each committee a secretary from Parliament's staff; the statute of the Secretariat General and the identity of the Secretary General (commonly Adnan Daher): **U** from primary sources this session.
- **Standing committees** — Art. 19 (elected after the Bureau and at each October session), **Art. 20** (list of 16, as amended 1999–2003), Art. 21 (max two memberships except Human Rights, Women & Child, IT), Art. 23 (each elects a chair and rapporteur by secret ballot within three days). Chairs as elected in the plenary of **21 October 2025** — https://www.lp.gov.lb/ContentRecordDetails?Id=34472 and PDF https://lp.gov.lb/backoffice//uploads/files/اللجان%20النيابية%202025-2026.pdf (P; names decoded from a garbled PDF font — verify each before publishing):

| # | Committee (ar) | English | Size | Chair (21/10/2025) | Rapporteur | lp.gov.lb page |
|---|---|---|---|---|---|---|
| 1 | المال والموازنة | Finance & Budget | 17 | Ibrahim Kanaan | Ali Fayyad | ViewContentRecords.aspx?id=29 |
| 2 | الإدارة والعدل | Administration & Justice | 17 | George Adwan | George Atallah | id=32 |
| 3 | الشؤون الخارجية والمغتربين | Foreign Affairs & Emigrants | 17 | Fadi Alameh | Hagop Pakradounian | id=34 |
| 4 | الأشغال العامة والنقل والطاقة والمياه | Public Works, Transport, Energy & Water | 17 | Sajih Attieh | Mohammad Khawaja | id=36 |
| 5 | التربية والتعليم العالي والثقافة | Education, Higher Education & Culture | 12 | Hassan Mrad | Edgard Traboulsi | id=38 |
| 6 | الصحة العامة والعمل والشؤون الاجتماعية | Public Health, Labour & Social Affairs | 12 | Bilal Abdallah | Samer El Tom | id=40 |
| 7 | الدفاع الوطني والداخلية والبلديات | National Defence, Interior & Municipalities | 17 | Jihad al-Samad | Asaad Dergham | id=42 |
| 8 | شؤون المهجرين | Displaced | 12 | Hagop Pakradounian | Hussein Jashi | id=44 |
| 9 | الزراعة والسياحة | Agriculture & Tourism | 12 | Ayoub Hmayed | Adib Abdel Massih | id=30 |
| 10 | البيئة | Environment | 12 | Ghayath Yazbeck | Qassem Hashem | id=31 |
| 11 | الاقتصاد الوطني والتجارة والصناعة والتخطيط | National Economy, Trade, Industry & Planning | 12 | Farid Boustany | Nasser Jaber | id=33 |
| 12 | الإعلام والاتصالات | Media & Communications | 12 | Ibrahim Moussawi | Yassine Yassine | id=35 |
| 13 | الشباب والرياضة | Youth & Sports | 12 | Simon Abi Ramia | Raed Berro | id=37 |
| 14 | حقوق الإنسان | Human Rights | 12 | Michel Moussa | Nazih Matta | id=39 |
| 15 | المرأة والطفل | Women & Child | 12 | Inaya Ezzeddine | Adnan Traboulsi | id=41 |
| 16 | تكنولوجيا المعلومات | Information Technology | 9 | Tony Frangieh | Elias Hankache | id=43 |

Committees for the October 2026 session are not yet elected as of 2026-09-18 (the session opens the first Tuesday after 15 October).

### 5.4 The 2026 election — status as of 2026-09-18

**No parliamentary election was held in May 2026. The 2022 Parliament sits until 31 May 2028.**

- 2 Feb 2026: Decree 2438 called the electorate for 10 May 2026 (expatriates 1 and 3 May); 144,406 diaspora voters registered (S: https://en.wikipedia.org/wiki/2028_Lebanese_general_election).
- 2 March 2026 onward: war and mass displacement following Hezbollah's entry into the US/Israel–Iran war (S: https://carnegieendowment.org/middle-east/diwan/2026/03/lebanons-parliament-extends-its-term-under-fire).
- **9 March 2026**: first sitting of the second extraordinary session (Decree 2591) — "أقرّ المجلس القانون الرامي إلى تمديد ولاية مجلس النواب لمدة سنتين بأكثرية 76 نائباً" — https://www.lp.gov.lb/ContentRecordDetails?Id=35713 (P). Vote 76 for / 41 against / 4 abstentions (S: https://beirut-today.com/2026/03/09/parliament-approves-two-year-extension/). For: Development & Liberation (Amal), Loyalty to the Resistance (Hezbollah), Democratic Gathering (PSP), National Moderation, independents; against: Strong Republic (LF), Strong Lebanon (FPM), Kataeb (S).
- **Law 41 of 9/3/2026**, OG issue 11 (supplement) of 9/3/2026: "تمديد ولاية مجلس النواب تمديداً استثنائياً" to **31/5/2028** (P: Constitutional Council Decision 7/2026, below).
- Three challenges (reviews 6, 7 and 8/و/2026, FPM and LF/Kataeb/independent deputies).
- **Constitutional Council Decision 7/2026 of 7 April 2026**: "اولاً – في الشكل: قبول المراجعتين 6 و7 ...؛ ثانياً – في الأساس: رد المراجعات الثلاثة", reasoning "لا يرى هذا المجلس ان تمديد مجلس النواب لولايته حتى 31/5/2028 غير متناسب مع الظروف الاستثنائية" while affirming Parliament's duty to hold elections once the exceptional circumstances end. Signed by President Tannous Mechleb and SG Awni Ramadan. PDF: https://d3eoo4vpw0ewa2.cloudfront.net/documents/%D9%82%D8%B1%D8%A7%D8%B1_%D8%B1%D9%82%D9%85_7_2026.pdf (linked from https://www.cc.gov.lb/ar/القرارات/قرارات-دستورية-القوانين/) (P). Note: the listing page's summary label reads "إبطال" for this row; the decision text says **رد** (petitions rejected). Trust the text.
- No election decree for 2028 exists yet: **U**. No new Speaker election, results, turnout or blocs exist for 2026.

**Composition of the sitting Parliament (elected 15 May 2022, turnout 49.19%)** — S: https://en.wikipedia.org/wiki/2022_Lebanese_general_election and https://en.wikipedia.org/wiki/Parliament_of_Lebanon (bloc snapshot; labels and sizes shift). Parties: LF 19, FPM 17, Hezbollah 15, Amal 15, PSP 8, Kataeb 4, Tashnag 3, Marada 2, Independence Movement 2, Change MPs and independents the rest. Blocs (indicative): Strong Republic 19; Loyalty to the Resistance 15; Development & Liberation 15; Strong Lebanon 13; Democratic Gathering 8; Forces of Change 8; National Moderation 6; Kataeb 5; National Compatibility 5; Independent Consultative Gathering 4; Independent National Bloc 3; Human Homeland Project 3; Renewal 3; Change Alliance 3; Tashnag 2; Popular Nasserist 1; ReLebanon 1; Islamic Group 1; independents ~13; 1 vacancy. Exact bloc sizes as of September 2026: **U** (lp.gov.lb blocs page not retrievable).

**Government as of 2026-09-18**: Nawaf Salam's cabinet, which obtained a renewed vote of confidence on 16 September 2026 (68/12/2) after a policy-statement debate under Rules Arts. 136–137 — https://www.lp.gov.lb/ContentRecordDetails.aspx?Id=36998 (P). No resignation or reshuffle mentioned.

### 5.5 Positions inside Parliament to model

Speaker and Deputy Speaker (elected, full term, removable once by 2/3 after two years); two secretaries and three commissioners (annual); 16 committee chairs and rapporteurs (annual, October); oldest member presides the inaugural sitting and two youngest act as secretaries (Art. 44, procedural ex officio); blocs and bloc leaders (political groupings with no legal basis in the Constitution or Rules — model as dated memberships, not positions); the seven deputy members of the Supreme Council for the Trial of Presidents and Ministers (elected 26 July 2022).

---

## 6. Implications for the civicleb model

Concrete schema and taxonomy changes recommended on the basis of the above.

1. **Add subtypes.** `state_company` (MEA, Intra, Casino du Liban, Alfa/touch, the airport company), `territorial_unit` (governorates and districts, phase 2), and a `position_class` attribute rather than a subtype for grade one. Keep `legalForm` (`ministry | directorate_general | public_institution | autonomous_service | independent_authority | central_bank | company | temporary_committee | local_authority`) distinct from the rendering `subtype`.
2. **Position attributes.** `grade: "one" | "one_equivalent" | null`; `confession` and `confessionBasis: constitution | pact | custom`; `confessionSource` (URL) so the sect legend (decision Q8) can show its basis per position. Only Speaker/Deputy Speaker/President/PM/Deputy PM are `pact`; nothing is `constitution` except the 128 seats' `seatConfession` (Art. 24 + Law 44/2017 Annex 1); everything else is `custom`.
3. **Edge metadata for appointments.** Give `appoints` a structured payload: `{ instrument: "presidential_decree" | "decree" | "decree_in_com" | "decision" | "election", proposer: nodeId, signatories: [nodeId], majority: "2/3" | "simple" | null, advisory: [nodeId], legalSource: {...} }`. The source of an `appoints` edge is the *deciding* authority (Council of Ministers for all grade-one and equivalents), never the signatories.
4. **`oversees.kind`.** Distinguish `tutelage` (minister → public institution, Decree 4517), `audit` (Court of Audit), `inspection` (Central Inspection), `civil_service` (CSB), `financial_control` (MoF controller), `parliamentary` (committee → ministry), `prudential` (BCC → banks).
5. **Status vocabulary.** Keep `filled | acting | caretaker | vacant | never_constituted` and add `extended` for bodies whose term was prolonged by law (Parliament under Law 41/2026) and `delegated` for powers exercised by another body (Art. 62 presidential vacancy → CoM). Tenures need `endReason: term | resignation | dismissal | death | retirement | extension`.
6. **Never-constituted constitutional bodies** as first-class nodes: Senate (Art. 22), National Committee for abolishing confessionalism (Art. 95), Electricity Regulatory Authority before Sept 2025 (now `filled`), the Supreme Council's judicial bench.
7. **Boards.** Model each public institution's board as a `commission`-type child node with `administers` → institution and seat positions; the DG as `dept_head`. Chair and DG may coincide (record via the same person holding two positions), not by a merged position.
8. **Multi-stage processes** (judicial transfers; the 2025 appointments pipeline) need either `advises` edges with `stage` or a lightweight `process` node. Recommend `advises` with `stage` for v1.
9. **Legal-source records** should support `instrument: constitution | taif | law | legislative_decree | decree | decree_in_com | decision | rules_of_procedure | custom` — "legislative decree" (مرسوم اشتراعي) and "decree in the Council of Ministers" are distinct from an ordinary decree and are the majority of creating texts in §1.
10. **Parliament pill.** 128 seat nodes keyed by `(majorDistrict, minorDistrict, seatConfession, ordinal)` from §5.2; the six Art. 112 expatriate seats should exist as `never_constituted` seat nodes flagged `suspended (Law 8/2021)` so the 134-seat contingency is representable.
11. **Dates to hard-code in the changes feed baseline**: presidential election 9 Jan 2025; Salam designation 13 Jan 2025; cabinet 8 Feb 2025; confidence 26 Feb 2025; appointments mechanism 20 Mar 2025; Army Commander 13 Mar 2025; BDL Governor 27 Mar 2025; committee elections 21 Oct 2025; Judicial Organisation Law 36 of 5 Jan 2026 (Constitutional Council 1/2026, outcome to verify); Parliament extension Law 41 of 9 Mar 2026 and Decision 7/2026 of 7 Apr 2026; renewed confidence 16 Sep 2026.

### Open items to verify before publishing nodes (UNVERIFIED list)

Original decree number of the Council of Ministers' internal regulation; Court of Audit composition/appointment articles (Leg. Decree 82/1983) and State Council president's article (Leg. Decree 10434/1975); the creating amendment of الهيئة العليا للتأديب; CAS Decree 1793/1979 text; ESC "environmental" renaming law and March 2026 decree number; the outcome of Constitutional Council Decision 1/2026 on Judicial Organisation Law 36/2026 and whether the new HJC composition is in force; 2025 appointments-mechanism decision number and text; the 2020 appointment-mechanism law and its Council decision; Law 17/1990 (ISF) and Leg. Decree 139/1959 (General Security) DG-appointment articles; BDL Art. 18/19 split; names of the seven deputy members of the Supreme Council; Secretary General of Parliament; current bloc sizes; sect allocation of six governorships and of the Army Intelligence, HDC SG, Parliament SG, DG Foreign Affairs and DG Interior posts; whether Bureau secretaries/commissioners were re-elected in October 2025.

---


# 02 — Inventory of the Lebanese executive (ministries, DGs, public institutions, regulators, SOEs)

Research date: 2026-09-18. Government in office: 78th government (Nawaf Salam), formed by Decree 53 of 8 February 2025, confidence vote 26 February 2025. No ministerial change recorded to 2026-09-18 (pcm.gov.lb minister page, footer "last update 18 أيلول 2026"; Wikipedia "Cabinet of Nawaf Salam", last edited 28 June 2026).

Legend: **UNVERIFIED** = not confirmed against a primary source during this pass. *Paper-only* = created by law but never (or not currently) constituted.

Status: COMPLETE for this pass (2026-09-18); see section 5, item 7, for open items.

## 0. The Council of Ministers as of 2026-09-18 (24 seats)

Source: pcm.gov.lb "وزراء المجلس - البيان الوزاري", https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=13587 (fetched 2026-09-18; Decree 52 of 8-2-2025 designates Salam, Decree 53 of 8-2-2025 forms the government); cross-checked with https://en.wikipedia.org/wiki/Cabinet_of_Nawaf_Salam (last edited 2026-06-28) and https://fr.wikipedia.org/wiki/Gouvernement_Nawaf_Salam. Party/confession columns come from the Wikipedia table (secondary; the pcm page does not give them). All holders in office continuously since 8-2-2025 unless noted.

| # | Portfolio (AR) | Portfolio (EN) | Minister (EN / AR) | Affiliation | Confession |
|---|---|---|---|---|---|
| 1 | رئيس مجلس الوزراء | Prime Minister | Nawaf Salam / نواف سلام | Independent | Sunni |
| 2 | نائب رئيس مجلس الوزراء | Deputy Prime Minister (no portfolio) | Tarek Mitri / طارق متري | Independent | Greek Orthodox |
| 3 | وزير المالية | Finance | Yassine Jaber / ياسين جابر | Amal | Shia |
| 4 | وزير الثقافة | Culture | Ghassan Salamé / غسان سلامة | Independent | Greek Catholic |
| 5 | وزير الدفاع الوطني | National Defence | Michel Menassa / ميشال منسى | Independent | Greek Orthodox |
| 6 | وزير الطاقة والمياه | Energy and Water | Joe Saddi / جوزيف الصدي | Lebanese Forces | Greek Orthodox |
| 7 | وزير السياحة | Tourism | Laura Khazen Lahoud / لورا الخازن لحود | Independent | Maronite |
| 8 | وزير الشؤون الاجتماعية | Social Affairs | Haneen Sayed / حنين السيد | Independent | Sunni |
| 9 | وزير الخارجية والمغتربين | Foreign Affairs and Emigrants | Youssef (Joe) Raggi / يوسف رجي | Lebanese Forces | Maronite |
| 10 | وزير الاقتصاد والتجارة | Economy and Trade | Amer Bisat / عامر البساط | Independent | Sunni |
| 11 | وزير المهجرين + وزير دولة لشؤون تكنولوجيا المعلومات والذكاء الاصطناعي | Displaced + Minister of State for IT and AI | Kamal Shehadi / كمال شحادة | Lebanese Forces | Protestant |
| 12 | وزير الداخلية والبلديات | Interior and Municipalities | Ahmad al-Hajjar / أحمد الحجار | Independent | Sunni |
| 13 | وزير العدل | Justice | Adel Nassar / عادل نصار | Kataeb | Maronite |
| 14 | وزير الاتصالات | Telecommunications | Charles Hage / شارل الحاج | Independent | Maronite |
| 15 | وزير الشباب والرياضة | Youth and Sports | Nora Bayrakdarian / نورا بايراقداريان | Tashnag | Armenian Orthodox |
| 16 | وزير التربية والتعليم العالي | Education and Higher Education | Rima Karami / ريما كرامي | Independent | Sunni |
| 17 | وزير الصناعة | Industry | Joe Issa el-Khoury / جو عيسى الخوري | Lebanese Forces | Maronite |
| 18 | وزير دولة لشؤون التنمية الإدارية | Minister of State for Administrative Development (OMSAR) | Fadi Makki / فادي مكي | Independent | Shia |
| 19 | وزير العمل | Labour | Mohammad Haidar / محمد حيدر | Hezbollah | Shia |
| 20 | وزير الأشغال العامة والنقل | Public Works and Transport | Fayez Rasamny / فايز رسامني | PSP | Druze |
| 21 | وزير الزراعة | Agriculture | Nizar Hani / نزار هاني | PSP | Druze |
| 22 | وزير الإعلام | Information | Paul Morcos / بول مرقص | Independent | Greek Catholic |
| 23 | وزير البيئة | Environment | Tamara el-Zein / تمارا الزين | Amal | Shia |
| 24 | وزير الصحة العامة | Public Health | Rakan Nasreddine / ركان ناصر الدين | Hezbollah | Shia |

Structure: 22 portfolio ministries + OMSAR (a ministerial office without a ministry) + one Minister of State title (IT and AI) held jointly with the Displaced portfolio = 24 members, 22 distinct ministries. The Deputy PM holds no portfolio. Presidency of the Council of Ministers (رئاسة مجلس الوزراء) is itself an administration with a Directorate General headed by Judge Mahmoud Makkieh, Secretary General of the Council of Ministers (https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=13090, live 2026-09-18).


## 1. Ministries: names, legal basis, directorates general, attached bodies

Method and shared sources. The skeleton of every ministry (its directorates general and the bodies under its tutelage) is taken from the organisation charts published by the Civil Service Board, "هيكليات الوزارات والإدارات العامة" and "هيكليات المؤسسات العامة", https://www.csb.gov.lb/ar/الهيكليات/ (charts dated 1/3/2019 for ministries and 25/4/2023 for public institutions; PDFs downloaded 2026-09-18, cited below as "CSB chart"). Officeholders come from Council of Ministers decision records on pcm.gov.lb where available (cited as "pcm PDF 2026-07-09" = https://www.pcm.gov.lb/Library/Files/مقررات%20جلسة%209%20تموز%202026.pdf and "pcm PDF 2026-08-07" = https://www.pcm.gov.lb/Library/Files/مقررات%20جلسة%20مجلس%20الوزراء%20بتاريخ%207%20آب%202026.pdf, both fetched 2026-09-18), otherwise from press reports of cabinet sessions (Lebanon Debate = https://www.lebanondebate.com/article/NNN). Names of ministries in Arabic are verbatim from pcm.gov.lb (section 0); French names from fr.wikipedia "Gouvernement Nawaf Salam". Website HTTP codes are from curl on 2026-09-18 (403/"Validation request" = Cloudflare bot check, site exists).

Context on grade-1 posts (الفئة الأولى = DGs and equivalents), as of 2026-09-10: OMSAR counts 35 grade-1 posts vacant, 12 of them "on the way" (heads of Sidon and Tyre ports, governor of Nabatieh, chair/DG of the Traffic and Vehicles Management Authority, secretary general of the Higher Council for Privatisation and PPP, DGs of Youth and Sports (acting since 2024), Tourism (acting since July 2023) and Labour (applications closed 2026-08-16)); الدولية للمعلومات counts 48 vacant or acting, adding the DG of Emigrants, the Constitutional Council members (expired Aug 2025), the National Audiovisual Council and the presidency of the Lebanese University. Source: An-Nahar via kataeb.org, 2026-09-10, https://kataeb.org/articles/sl-241234 ; janoubia 2026-09-10 https://janoubia.com/2026/09/10/ . The cabinet's appointment mechanism for grade-1 posts (call for candidates, committee interviews, minister shortlists, cabinet decides) was adopted 2025-03-20 (https://www.lebanondebate.com/article/690167); an age band of 35–54 for outside-cadre appointments was added 2026-04-30 (https://www.lebanondebate.com/article/830543).

### 1.0 Presidency of the Council of Ministers — رئاسة مجلس الوزراء — Présidence du Conseil des ministres

- Site: https://www.pcm.gov.lb (200). Legal basis: Decree 4717 of 1982 organising the Council of Ministers (cited in https://www.akhbaralyawm.com/news/545815); the Directorate General of the PCM is organised per pcm.gov.lb https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=18 (4 branches: ministerial affairs, protocol, legal, technical; Central Archives; Diwan; Official Gazette service; IT centre). CSB chart "رئاسة مجلس الوزراء" (2026-04 PDF).
- DG / Secretary General of the Council of Ministers: Judge Mahmoud Adnan Makkieh (القاضي محمود مكيّه), https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=13090 (live 2026-09-18). Since 2026-06-04 also acting DG of the Cannabis Cultivation Regulatory Authority (https://www.lebanondebate.com/article/844185).
- Bodies attached to the PCM (list on https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=4211, page dated 2013 but still the official list): oversight bodies (Civil Service Board, Central Inspection, Higher Disciplinary Authority, Court of Audit); administrations (Central Administration of Statistics; General Directorate of State Security); the Sunni, Jaafari and Druze religious courts and Diwan; the two Iftas; the Shia, Alawite and Druze councils and the Druze Sheikh al-Aql; councils and funds (CNRS, CDR, Council of the South, Secretariat of the Higher Defence Council, Higher Council for Privatisation, Economic and Social Council, Central Fund for the Displaced, Higher Relief Council, Hajj and Umra Affairs Authority); public institutions (Elyssar, Public Institution for Consumer Markets, IDAL, National Institute of Administration (ENA), National Archives Institution (Decree 832 of 17-1-1978), State Employees Cooperative); plus NCLW, Higher Council for Childhood, Lebanese-Syrian Higher Council, Public Sector Projects and Studies Centre, National Human Rights Commission. These are inventoried in section 2; the CSB chart confirms "سلطة الوصاية: رئاسة مجلس الوزراء" for the Central Fund for the Displaced, Council of the South, National Archives and Consumer Markets institution, and "سلطة الوصاية: مجلس الخدمة المدنية" for ENA and the State Employees Cooperative.
- Recent PCM-level appointments: Higher Council for Privatisation and PPP secretary general Jocelyne Jabbour (2026-09-17, https://www.lebanondebate.com/article/879687 ; pcm session page https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=27545); State Employees Cooperative DG Nazih Hammoud (2026-06-25, https://www.lebanondebate.com/article/852335); Public Procurement Authority president Jean Ellieh (pcm PDF 2026-08-07).

### 1.1 Ministry of Finance — وزارة المالية — Ministère des Finances

- Site: https://www.finance.gov.lb (200). Organising text: Decree 2869 of 16-12-1959 and Decree 8331 of 30-12-1961 (organisation of the ministry) as amended — **UNVERIFIED** (not re-checked against legallaw); the ministry publishes its 2026 organisation chart at https://www.finance.gov.lb/en-us/About/MoF/Documents/Organizational%20Chart-%202026ar.pdf (linked from https://www.finance.gov.lb/ar-lb/About/MoF/Pages/Organizational%20Chart.aspx), which is the source for the table below.
- Minister: Yassine Jaber (since 2025-02-08, Decree 53).

| DG post (AR / EN) | Holder | Status | Source |
|---|---|---|---|
| مديرية المالية العامة — Directorate General of Finance (المدير العام للمالية) | George Maarawi (جورج المعراوي) | Confirmed in post "بالأصالة" by cabinet 2025-05-29 (had been acting) | https://www.akhbaralyawm.com/news/450251 ; kataeb.org 2025-05-29; MoF chart 2026 |
| مديرية الواردات — Revenue Directorate | Mohammad Abdel Sattar al-Wafai | acting (بالتكليف) | MoF chart 2026 |
| مديرية الخزينة — Treasury | Rana Karam | acting | MoF chart 2026 |
| مديرية الصرفيات — Disbursements | Rania Diab | filled | MoF chart 2026 |
| مديرية الدين العام — Public Debt | Rania al-Chaar | acting | MoF chart 2026 |
| مديرية الموازنة ومراقبة النفقات — Budget and Expenditure Control | Carole Abi Khalil | filled | MoF chart 2026 |
| مديرية الشؤون الإدارية — Administrative Affairs | Victoria Moqaddasi | acting | MoF chart 2026 |
| مديرية المحاسبة العامة — Public Accounting | Abdel Hafiz Soubra | acting | MoF chart 2026 |
| مديرية الضريبة على القيمة المضافة — VAT Directorate | Bilal Chaalan | acting | MoF chart 2026 |
| المديرية العامة للشؤون العقارية — DG of Real Estate Affairs (Cadastre) | **UNVERIFIED** — a separate appointment was announced as pending when Maarawi was confirmed (Lebanon Debate headline "على أن يُعيّن اسم آخر مديرا للشؤون العقارية", 2025-05) | vacant/acting UNVERIFIED | Google News RSS 2025-05 (raw/gn_cadastre) |
| المديرية العامة للجمارك — DG of Customs (مدير عام الجمارك) | Gracia Kazzi (غراسيا القزي) | appointed 2026-01-15 (contested: she is a defendant in the port-blast file) | https://www.lebanondebate.com/article/781308 ; https://www.lebanondebate.com/article/781765 ; https://www.lebanondebate.com/article/792038 |
| المجلس الأعلى للجمارك — Higher Council of Customs (president + 2 members) | President Brig. Misbah Khalil (مصباح الخليل); members Charbel Khalil, Louay Hajj Shehadeh | appointed 2026-01-15 | https://www.lebanondebate.com/article/781308 ; almarkazia 2026-01-15 |

Note: the Customs Administration is a dual structure (Higher Council of Customs created 1950 + DG of Customs), attached to the Minister of Finance: https://ar.wikipedia.org/wiki/الجمارك_اللبنانية ; site http://www.customs.gov.lb (200).

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| إدارة الجمارك (المجلس الأعلى للجمارك + المديرية العامة للجمارك) — Customs | administration attached to MoF | Customs Law (Decree-Law 4461/2000 as amended) — UNVERIFIED number | see table above | http://www.customs.gov.lb |
| معهد باسل فليحان المالي والاقتصادي — Institut des Finances Basil Fuleihan | training institute inside MoF (autonomous budget) | Law 105 of 1996 (creation as "Institut des Finances") — UNVERIFIED | President: **UNVERIFIED** | https://www.institutdesfinances.gov.lb |
| مديرية اليانصيب الوطني — National Lottery Directorate | directorate of MoF | UNVERIFIED | DG: **UNVERIFIED**; cabinet returned sports betting to the Lottery in 2026 (almodon headline "من الرياضة والبينغو للأونلاين.. اليانصيب الوطني يستعيد دوره", raw/gn_lottery) | http://www.lnl.gov.lb (untested) |
| مصرف لبنان — Banque du Liban | central bank, independent legal person; governor proposed by MoF | Code of Money and Credit, Decree 13513 of 1-8-1963 | Governor Karim Souaid since 2025-03-27 | see section 2 |
| لجنة الرقابة على المصارف، هيئة الأسواق المالية، هيئة التحقيق الخاصة، لجنة مراقبة هيئات الضمان | see section 2 (Insurance Control Commission is inside the Ministry of Economy, not MoF) | | | |
| تعاونية موظفي الدولة — State Employees Cooperative | public institution, tutelage CSB (CSB chart) | UNVERIFIED | DG Nazih Hammoud, 2026-06-25 | https://www.cfe.gov.lb (untested) |

### 1.2 Ministry of Economy and Trade — وزارة الاقتصاد والتجارة — Ministère de l'Économie et du Commerce

- Site: https://www.economy.gov.lb (403 Cloudflare; exists). Organising text: Decree 2229 of 1959? — **UNVERIFIED**; CSB chart for the ministry is an empty PDF in the CSB set ("2026_04_وزارة-الاقتصاد-والتجارة.pdf" extracted 0 bytes).
- Minister: Amer Bisat (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للاقتصاد والتجارة — DG of Economy and Trade | Mohammad Abou Haidar (محمد أبو حيدر) | filled; also appointed Government Commissioner to the National Competition Authority 2026-08-07 | pcm PDF 2026-08-07 ("السيّد محمد أبو حيدر، المدير العام لوزارة الإقتصاد والتجارة") |
| المديرية العامة للحبوب والشمندر السكري — DG of Cereals and Sugar Beet (المكتب) | **UNVERIFIED** | | CSB/ministry structure; raw/gn_cereals |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| الهيئة الوطنية للمنافسة (مجلس المنافسة) — National Competition Authority | independent authority created by the Competition Law | Law 281 of 2022 (competition) — number UNVERIFIED | President Judge Rola Akoum, Vice-president Judge Stephanie Saliba, Government Commissioner Mohammad Abou Haidar (2026-08-07, 4-year terms); council members Adnan Rammal, Jamil Jalilati, Pascale Daher, Eliane Nehme, Anis Bou Diab (2026-09-10) | pcm PDF 2026-08-07 ; https://www.lebanondebate.com/article/877452 |
| لجنة مراقبة هيئات الضمان — Insurance Control Commission | commission inside MoET (Decree-Law 9812/1968 on insurance) | UNVERIFIED | new president appointed 2025/26 (An-Nahar headline "تعيين رئيس جديد للجنة مراقبة هيئات الضمان"; Nadim Haddad had been acting president per Elnashra) — name/date **UNVERIFIED** | https://www.icc.gov.lb (untested) |
| مصلحة حماية المستهلك — Consumer Protection Directorate | directorate of MoET | Law 659 of 2005 | — | |
| المؤسسة العامة للأسواق الاستهلاكية — Public Institution for Consumer Markets | public institution, tutelage PCM (CSB chart) | UNVERIFIED | board **UNVERIFIED** (largely dormant) | |
| مؤسسة المقاييس والمواصفات اللبنانية (ليبنور) — LIBNOR | public institution, tutelage Ministry of Industry (CSB chart lists it under Industry) | Law of 23-7-1962 | see 1.15 | http://www.libnor.gov.lb (200) |
| المجلس الاقتصادي والاجتماعي — Economic and Social Council | see section 2 (attached to PCM) | | | |

### 1.3 Ministry of Interior and Municipalities — وزارة الداخلية والبلديات — Ministère de l'Intérieur et des Municipalités

- Site: https://moim.gov.lb (200; the old interior.gov.lb redirects). Organising text: Decree 4082 of 14-10-2000 pursuant to Law 247 of 7-8-2000 (rename to "Interior and Municipalities"), earlier organisation 8-9-1961: https://ar.wikipedia.org/wiki/وزارة_الداخلية_والبلديات_(لبنان) . Structure list: https://moim.gov.lb/structure/ .
- Minister: Brig. Gen. (ret.) Ahmad al-Hajjar (since 2025-02-08).

| DG / grade-1 post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة لقوى الأمن الداخلي — DG of Internal Security Forces | Maj. Gen. Raed Abdallah (اللواء رائد عبدالله) | appointed by decree 2025-03-13 (security appointments package) | https://www.imlebanon.org/2025/03/13/ (Elnashra/kataeb 2025-03-13 "صدور مراسيم الترقية والتعيين لقادة الأجهزة الأمنية الجدد"); en.wikipedia https://en.wikipedia.org/wiki/Internal_Security_Forces — **name UNVERIFIED against the decree text** |
| المديرية العامة للأمن العام — DG of General Security | Maj. Gen. Hassan Choucair (اللواء حسن شقير) | appointed 2025-03-13 | same package; https://en.wikipedia.org/wiki/General_Directorate_of_General_Security — **name UNVERIFIED against decree** |
| المديرية العامة للأحوال الشخصية — DG of Personal Status (civil registry) | **UNVERIFIED** | | https://www.dgcs.gov.lb |
| المديرية العامة للإدارات والمجالس المحلية — DG of Local Administrations and Councils | **UNVERIFIED** | | CSB chart |
| المديرية العامة للشؤون السياسية واللاجئين — DG of Political Affairs and Refugees | **UNVERIFIED** | | CSB chart |
| المديرية العامة للدفاع المدني — DG of Civil Defence | Brig. Gen. (surname) Khreich (خريش) — first name UNVERIFIED; predecessor Gen. Raymond Khattar | new DG appointed by cabinet 2026-01-30 | https://www.lebanondebate.com/article/786051 ; Elnashra 2026-09-11 "عون بحث ... مع خريش بعمل الدفاع المدني"; http://www.civildefense.gov.lb |
| المحافظون — Governors (8 mohafazat + Beirut) | Nabatieh governor vacant; a "package" replacement of all governors under discussion (2026-09) | | https://kataeb.org/articles/sl-241234 |
| المفتشية العامة لقوى الأمن الداخلي; مجلس الأمن الداخلي المركزي; جهاز أمن المطار; جهاز اعتراض الاتصالات; الإدارة المركزية لمكافحة المخدرات | units listed on the ministry structure page | | https://moim.gov.lb/structure/ |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| هيئة إدارة السير والآليات والمركبات — Traffic, Vehicles and Motor Vehicles Management Authority (TMO) | public institution, tutelage MoIM (CSB chart 2023) | Traffic Law 243 of 22-10-2012 | chair/DG **vacant** (OMSAR list, 2026-09) | https://tmo.gov.lb (200) |
| الصندوق التعاوني للمختارين — Mukhtars' Cooperative Fund | public institution (CSB chart) | UNVERIFIED | board UNVERIFIED | |
| الصندوق البلدي المستقل — Independent Municipal Fund | treasury account managed by DG Local Administrations (not a legal person) | Decree-Law 118/1977 (municipalities) | — | |
| المديرية العامة للأمن العام / قوى الأمن الداخلي | security services (see part 04 of this research) | Law 17 of 6-9-1990 (ISF); Decree-Law 139/1959 (General Security) — UNVERIFIED numbers | | https://isf.gov.lb ; http://general-security.gov.lb |

### 1.4 Ministry of Foreign Affairs and Emigrants — وزارة الخارجية والمغتربين — Ministère des Affaires étrangères et des Émigrés

- Site: https://www.mfa.gov.lb (200; emigrants.gov.lb legacy). History and legal basis: https://ar.wikipedia.org/wiki/وزارة_الخارجية_والمغتربين_(لبنان) (Decree 1220 of 30-3-1953 converting legations to embassies; merger with the Ministry of Emigrants by Law 247/2000 — the latter UNVERIFIED). CSB chart (2 pages): Secretariat General (الأمانة العامة) heading the diplomatic administration, plus المديرية العامة للمغتربين.
- Minister: Youssef Raggi (since 2025-02-08).

| Post | Holder | Status | Source |
|---|---|---|---|
| الأمين العام لوزارة الخارجية — Secretary General (grade-1, ambassador) | **UNVERIFIED** (Ambassador Hani Chmaitelli held the post from 2020; whether replaced in 2025-26 not established) | | CSB chart; raw/ld search |
| المدير العام للمغتربين — DG of Emigrants | **vacant** (listed among vacant grade-1 posts, 2026-09-10) | vacant | https://kataeb.org/articles/sl-241234 |

Attached bodies: none with separate legal personality; the Lebanese diplomatic missions are units of the ministry.

### 1.5 Ministry of National Defence — وزارة الدفاع الوطني — Ministère de la Défense nationale

- Site: https://www.mod.gov.lb (200). Legal basis: National Defence Law, Decree-Law 102 of 16-9-1983 as amended (full list of amendments on https://www.mod.gov.lb/AboutMOD/Laws ; art. 17 creates the Military Chamber (الغرفة العسكرية) with the ministry, https://www.mod.gov.lb/AboutMOD/Structure). CSB chart: the ministry's civilian side is the Military Chamber; attached "مؤسسة الاقتصاد" (military cooperative), mutual funds for officers, NCOs and employees.
- Minister: Michel Menassa (since 2025-02-08).

| Post | Holder | Status | Source |
|---|---|---|---|
| قائد الجيش — Commander of the Lebanese Armed Forces | Gen. Rodolphe Haykal (العماد رودولف هيكل) | appointed 2025-03-13 (cabinet), decree same day | https://en.wikipedia.org/wiki/2025_in_Lebanon ; https://en.wikipedia.org/wiki/Rodolphe_Haykal |
| رئيس الأركان — Chief of Staff | **UNVERIFIED** (Maj. Gen. Hassan Audi has been reported as chief of staff since 2024; not confirmed for 2026) | | |
| المفتش العام (المجلس العسكري) — Inspector General | Maj. Gen. Fadi Makhoul (اللواء الركن فادي مخول) | decree 2025-04-03 | Elnashra 2025-04-03 "رئاسة الجمهورية: صدور مراسيم تعيين وترقية أعضاء المجلس العسكري"; https://www.lebanondebate.com (2025-04-03 profile) |
| المدير العام للإدارة (المجلس العسكري) — DG of Administration | Maj. Gen. Mohammad al-Amine (اللواء الركن محمد الأمين) | decree 2025-04-03 | https://www.mtv.com.lb/news/1562288 (2025-04-03) |
| رئيس الغرفة العسكرية — Head of the Military Chamber | Brig. Gen. Gerges al-Ghazzi (العميد الركن جرجس الغزي) | since 2024-11-19 | https://www.mod.gov.lb/AboutMOD/Structure (list of heads, live 2026-09-18) |
| الأمين العام للمجلس الأعلى للدفاع — SG of the Higher Defence Council | **UNVERIFIED** (Maj. Gen. Mohammad al-Mustafa has held it since 2021 per press; 2026 status not checked) | | Elnashra 2025-05-22 headline |
| رئيس المحكمة العسكرية — President of the Military Court | Brig. Gen. Wassim Fayyad (العميد وسيم فياض) | appointed by the minister 2025-01-15 | Elnashra 2025-01-15 https://www.elnashra.com/news/show/1706543 |

Attached bodies: Lebanese Armed Forces (https://www.lebarmy.gov.lb , 200); Military Court (Military Justice Code, Law of 13-4-1968); Higher Defence Council (chaired by the President; see section 2); مؤسسة الاقتصاد, صناديق التعاضد (CSB chart). Security services are treated in part 04 of this research.

### 1.6 Ministry of Justice — وزارة العدل — Ministère de la Justice

- Site: https://www.justice.gov.lb (200). Legal basis: name "وزارة العدل" fixed by Decree-Law 111 of 12-6-1959 (organisation of the ministry); history back to Decision 86 of 29-10-1920: https://ar.wikipedia.org/wiki/وزارة_العدل_(لبنان) ; departments list https://www.justice.gov.lb/index.php/ministry-departments/2 .
- Minister: Adel Nassar (since 2025-02-08).

| Post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة لوزارة العدل — Director General of the Ministry of Justice | Judge Maysam Nouairi (القاضية ميسم النويري) | filled (in post since 2011) | https://www.justice.gov.lb/index.php/department-details/17/2 (live 2026-09-18) |
| هيئة التشريع والاستشارات — Legislation and Consultations Authority (president, judge) | UNVERIFIED | | https://www.justice.gov.lb/index.php/department-details/15/2 |
| هيئة القضايا — State Litigation Authority (president, judge) | UNVERIFIED | | https://www.justice.gov.lb/index.php/department-details/18/2 |
| مديرية السجون; مصلحة إصلاح الأحداث; مصلحة الطب الشرعي; السجل التجاري; مركز المعلوماتية القضائية | directorates/services of the ministry | | https://www.justice.gov.lb/index.php/ministry-departments/2 |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| معهد الدروس القضائية — Institute of Judicial Studies | institute of the ministry | Judicial Organisation Law, Decree-Law 150/1983 | UNVERIFIED | https://www.justice.gov.lb/index.php/department-details/3/2 |
| صندوق تعاضد القضاة — Judges' Mutual Fund | public institution | Law 1980s — UNVERIFIED | government commissioner formerly Judge Makkieh (pcm bio) | https://www.justice.gov.lb/index.php/department-details/9/2 |
| الهيئة الوطنية للمفقودين والمخفيين قسراً — National Commission for the Missing and Forcibly Disappeared | independent commission created by Law 105 of 2018 | Law 105 of 30-11-2018 | chair Judge Joseph Samaha + 9 members appointed by cabinet 2025 (June/July) | https://www.lebanondebate.com/article/721341 |
| Courts, Higher Judicial Council, State Council, Court of Audit, Judicial Inspection | judiciary — part 03 of this research; note cabinet appointed Judge Ahmad Rami al-Hajj as Cassation Prosecutor and Judge Oussama Mneimneh as head of Judicial Inspection on 2026-04-30 (https://www.lebanondebate.com/article/830543) | | | |

### 1.7 Ministry of Telecommunications — وزارة الاتصالات — Ministère des Télécommunications

- Site: https://www.mpt.gov.lb (200). Legal basis: Law 11/80 of 17-5-1980 (rename from PTT and reorganisation) and Decree 3585 of 25-10-1980; Telecommunications Law 431 of 22-7-2002 reorganised the ministry and created the TRA and "Liban Telecom" (CSB chart, page 1, notes 1–3). A new organisation decree for the ministry was approved by cabinet on 2026-07-09 (pcm PDF 2026-07-09: "مشروع مرسوم تنظيم وزارة الإتصالات وتوزيع الصلاحيات ... وتحديد ملاكها"); its number/publication UNVERIFIED.
- Minister: Charles Hage (since 2025-02-08).

| DG post (CSB chart, Law 11/80 structure) | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للاستثمار والصيانة — DG of Investment (Operations) and Maintenance | Bassel al-Ayoubi (باسل الأيوبي) | appointed 2025-11-05, succeeding Abdel Moneim Youssef | MTV 2025-11-05 "تعيين باسل الايوبي مديرا عاما للاستثمار والصيانة"; pcm PDF 2026-08-07 (COLIBAC board: "مدير عام الاستثمار والصيانة في وزارة الاتصالات السيد باسل الأيوبي") |
| المديرية العامة لإنشاء وتجهيز المواصلات السلكية واللاسلكية — DG of Construction and Equipment | **UNVERIFIED** | | CSB chart |
| المديرية العامة للبريد — DG of Post | **UNVERIFIED** (postal service is concessioned to LibanPost) | | CSB chart |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| هيئة أوجيرو — Ogero (هيئة إدارة واستثمار منشآت وتجهيزات شركة راديو أوريان) | public authority operating the fixed network on behalf of the ministry (created 1972) | Law of 1972 nationalising Radio-Orient — UNVERIFIED number; CSB chart lists it under the ministry | Chairman/DG Ahmad Oueidat (أحمد عويدات), appointed by cabinet 2025-05-29 after Imad Kreidieh's resignation; workers' status dispute before the State Council noted by cabinet 2026-07-09 | https://ogero.gov.lb (200); https://www.almodon.com/politics/2025/05/29/ ; https://www.akhbaralyawm.com/news/450251 ; pcm PDF 2026-07-09 |
| الهيئة المنظمة للاتصالات — Telecommunications Regulatory Authority | independent public institution (Law 431/2002) | Law 431 of 22-7-2002 | Board re-constituted 2025 (first since 2007; Lebanon Debate 741715): Chairwoman/CEO Dr Jenny Gemayel + 4 full-time members, 5-year non-renewable terms | https://www.tra.gov.lb/Board-Members (live 2026-09-18); https://www.lebanondebate.com/article/741715 |
| شركة تاتش (MIC 2) and ألفا (MIC 1) — state-owned mobile operators | Lebanese SALs wholly owned by the state (ministry) | Law 393/2002 (mobile licences; state ownership) — UNVERIFIED | Alfa: Rafic Haddad elected chairman-GM 2025-09-16 (Elnashra 1741637); touch: chairman **UNVERIFIED** | https://www.alfa.com.lb ; https://www.touch.com.lb |
| ليبان بوست — LibanPost | private concessionaire (not state-owned); postal service concession under the ministry | concession 1998 | — | https://www.libanpost.com |

### 1.8 Ministry of Energy and Water — وزارة الطاقة والمياه — Ministère de l'Énergie et de l'Eau

- Site: https://www.energyandwater.gov.lb (200). Legal basis: Decree-Law 20 of 26-3-1966 (creation of the Ministry of Hydraulic and Electrical Resources) — UNVERIFIED number; renamed by Law 247 of 7-8-2000; Water Law 221 of 29-5-2000 (four regional water establishments); Electricity Law 462 of 2-9-2002 (Electricity Regulatory Authority); Offshore Petroleum Resources Law 132 of 24-8-2010 (Lebanese Petroleum Administration). CSB chart lists three DGs and, as attached bodies, هيئة إدارة قطاع البترول, مؤسسة كهرباء لبنان, (هيئة تنظيم قطاع الكهرباء) and the four water establishments (Beirut & Mount Lebanon, North, Bekaa, South).
- Minister: Joe Saddi (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للنفط — DG of Oil (petroleum facilities) | Maurice Karkafi (موريس قرقفي) | appointed 2026-05-22 | https://www.mtv.com.lb/news/1698183 ; https://www.lebanondebate.com/article/839118 |
| المديرية العامة للموارد المائية والكهربائية — DG of Hydraulic and Electrical Resources | **UNVERIFIED** (appointment was expected at the 2025-10-23 session; outcome not confirmed) | | https://www.lebanondebate.com/article/755086 |
| المديرية العامة للاستثمار — DG of Investment (Exploitation) | **vacant** — appointment deferred by cabinet 2026-08-07 | vacant | MTV 2026-08-07 headline "إرجاء تعيين مدير عام الاستثمار في وزارة الطاقة" (raw/gn_resolved) |

| Attached body | Legal form | Creating text | Head / board (dated) | Site |
|---|---|---|---|---|
| مؤسسة كهرباء لبنان — Électricité du Liban (EDL) | public institution (industrial/commercial), tutelage MoEW | Decree 16878 of 10-7-1964 | Chairman of the board Eng. Nassib Nasr (non-executive, appointed 2026-09-17, posts of chair and DG separated); DG Eng. Kamal Hayek (retained); board members appointed 2026-02-16: Wassef Hneine, Nassib Nasr, Joelle Chaker, Hala Blouz, Ali Berro, Samer Hasnieh | https://www.edl.gov.lb (200); https://www.lebanondebate.com/article/879687 ; https://www.lebanondebate.com/article/791221 ; https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=27545 |
| هيئة تنظيم قطاع الكهرباء — Electricity Regulatory Authority | independent authority under Law 462/2002; *paper-only from 2002 until 2025* | Law 462 of 2-9-2002 | members first appointed by cabinet in 2025 (Lebanon Debate 741715, Sept/Oct 2025) and completed 2026-06-25 (Elnashra 1787605 headline); President Ziad Issam Samakieh appointed 2026-07-09 | pcm PDF 2026-07-09; https://www.lebanondebate.com/article/741715 |
| هيئة إدارة قطاع البترول — Lebanese Petroleum Administration (LPA) | public institution with board of 6, under MoEW | Law 132 of 2010; Decree 7968 of 2012 | new board appointed 2026-06-25 (Elnashra headline "تعيينات في ... قطاع البترول"); names **UNVERIFIED** | https://www.lpa.gov.lb (200) |
| مؤسسة مياه بيروت وجبل لبنان — Beirut & Mount Lebanon Water Establishment | public institution (Law 221/2000; Decree 14596/2005) | Law 221 of 29-5-2000 | DG **UNVERIFIED**; board renewed 2026-05-22 (cabinet appointed water-establishment boards) | https://www.ebml.gov.lb (401) |
| مؤسسة مياه لبنان الشمالي — North Lebanon Water Establishment | public institution | Law 221/2000 | board call for candidates 2026-02 (https://www.lebanondebate.com/article/793236), board appointed 2026-05-22 | https://www.nlwe.gov.lb (down) |
| مؤسسة مياه البقاع — Bekaa Water Establishment | public institution | Law 221/2000 | DG Antoine Maakaroun appointed 2025-11-20 | https://www.lebanondebate.com/article/764730 ; https://www.bwe.gov.lb (down) |
| مؤسسة مياه لبنان الجنوبي — South Lebanon Water Establishment | public institution | Law 221/2000 | board appointed 2026-05-22; DG **UNVERIFIED** | https://www.slwe.gov.lb (200) |
| المصلحة الوطنية لنهر الليطاني — Litani River Authority | public institution (مصلحة) with board | Law of 14-8-1954 | Board 2026-08-07 (3 years): President-DG Dr Sami Hassan Alawieh (renewed), Vice-president Yasser Fadil Abou al-Nasr, members Joe Louis Akiki, Farid Ramez Karam, Maroun Victor Nabhan, Khaled Mohammad Saad, Naji Ibrahim Berri | pcm PDF 2026-08-07; https://www.litani.gov.lb (200) |
| الهيئة الناظمة لزراعة القنب الهندي — Cannabis Cultivation Regulatory Authority | authority under Law 178 of 2020 (tutelage: PCM/MoEW? UNVERIFIED) | Law 178 of 2020 | DG post vacant; SG of the Council of Ministers Makkieh charged with running it 2026-06-04 | https://www.lebanondebate.com/article/844185 |

### 1.9 Ministry of Public Works and Transport — وزارة الأشغال العامة والنقل — Ministère des Travaux publics et des Transports

- Site: https://www.mpwt.gov.lb (200 but empty page on fetch; transportation.gov.lb behind bot check). Legal basis: Decree 2872 of 16-12-1959 (organisation of the Ministry of Public Works) — UNVERIFIED; merged with Transport by Law 247/2000. CSB chart (2025-06 PDF): four DGs — Roads and Buildings, Urban Planning, Land and Maritime Transport, Civil Aviation — plus attached ports and the railway authority.
- Minister: Fayez Rasamny (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للنقل البري والبحري — DG of Land and Maritime Transport | Brig. Gen. Mazen Basbous (العميد مازن بصبوص) | appointed 2026-05-22; handover 2026-07-02 | https://www.mtv.com.lb/news/1698183 ; https://www.akhbaralyawm.com/news/556559 |
| المديرية العامة للطرق والمباني — DG of Roads and Buildings | **UNVERIFIED** (post occupied; holder met MP Hawat 2026-04-25) | | https://www.akhbaralyawm.com/news/538034 |
| المديرية العامة للتنظيم المدني — DG of Urban Planning | **UNVERIFIED** (post occupied 2025-26; Feb 2026 press on a planned reassignment) | | https://www.akhbaralyawm.com/news/466185 (2025-07-28); tayyar.org 2026-02-23 |
| المديرية العامة للطيران المدني — DG of Civil Aviation | Amin Jaber (أمين جابر), styled "مدير الطيران المدني" | in post since the March 2025 airport reshuffle; whether by decree or assignment **UNVERIFIED** | An-Nahar 2025-08-02; alanba 2025-05-14; Elnashra 2025-03-12 |

| Attached body | Legal form | Creating text | Head / board (dated) | Site |
|---|---|---|---|---|
| اللجنة المؤقتة لإدارة واستثمار مرفأ بيروت — Temporary Committee for the Management and Operation of the Port of Beirut | ad-hoc committee (since 1990, no statute; not audited by the Court of Audit) | cabinet decisions since 1990 | Chairman Marwan al-Nafi (مروان النافي) + 6 members appointed 2025-11-06; cabinet approved in principle on 2026-08-07 the creation of "شركة مرفأ بيروت ش.م.ل." to replace it (draft law pending) | https://www.almodon.com/economy/2025/11/06/ ; pcm PDF 2026-08-07; https://www.portdebeyrouth.com (200) |
| مصلحة استثمار مرفأ طرابلس — Port of Tripoli Exploitation Authority | public institution (مصلحة) with board, tutelage MPWT (CSB chart) | UNVERIFIED | board appointed 2025-10-23 (chair name UNVERIFIED — press search term "إسكندر بندلي"); minister chaired board 2026-08-21 | Sawt Beirut 2025-10-23; Elnashra 2026-08-21 https://www.elnashra.com/news/show/1796389 ; https://www.tripoli-port.gov.lb |
| مصلحة استثمار مرفأ صيدا; مصلحة استثمار مرفأ صور — Sidon and Tyre port authorities | public institutions, tutelage MPWT (CSB charts 2023) | UNVERIFIED | heads **vacant**, "on the way" (OMSAR, 2026-09-10) | https://kataeb.org/articles/sl-241234 |
| مصلحة سكك الحديد والنقل المشترك — Railways and Public Transport Authority (RPTA) | public institution, tutelage MPWT (CSB chart) | Decree of 1961 — UNVERIFIED | Chair/DG **UNVERIFIED** (press search "زياد شيا"); active 2026 (electric bus service Jbeil–Beirut 2026-05-23; Tripoli–Abboudieh line tender 2026-05-15) | https://www.rpta.gov.lb (down); almarkazia 2026-05-23 |
| مؤسسة مطار بيروت الدولي ش.م.ل. — Beirut International Airport Corporation (new) | Lebanese SAL created by cabinet decision 2026-06 to operate the airport (contested by MPs Hassan Khalil et al.) | cabinet decision June 2026 (pcm session 2026-06-15?) — legal basis contested; **UNVERIFIED** | Chairman-GM Mohammad Chatila (2026-07-09, 5 years); non-executive members Walid Khaled Chkeir, Sleiman Jaber, Daisy Hanna, Adel Hosn al-Din, Jalal Haidar (2026-08-07, 5 years) | pcm PDF 2026-07-09; pcm PDF 2026-08-07; https://www.annahar.com 2026-06-16 "خلفيات إنشاء شركة مؤسسة مطار بيروت الدولي" |
| مطار رفيق الحريري الدولي / مطار الرئيس رينيه معوض (القليعات) — airports | state facilities run by DGCA; Qleiat airport inaugurated 2026-06-06; MEA contracted for its rehabilitation | | | https://www.beirutairport.gov.lb (200); https://en.wikipedia.org/wiki/2026_in_Lebanon |
| طيران الشرق الأوسط — Middle East Airlines (MEA) | SAL, 99% owned by Banque du Liban (not by the ministry) | | Chairman-DG Mohamad El-Hout | see section 3 |

### 1.10 Ministry of Social Affairs — وزارة الشؤون الاجتماعية — Ministère des Affaires sociales

- Site: https://www.socialaffairs.gov.lb (200). Legal basis: Law 212 of 2-4-1993 (creation, separating it from Labour), amended by Law 327 and Decree 5734: https://ar.wikipedia.org/wiki/وزارة_الشؤون_الاجتماعية_(لبنان) ; https://www.socialaffairs.gov.lb/ar/about/about-mosa . CSB chart: one DG; attached: المجلس الأعلى للطفولة, الهيئة الوطنية لشؤون المعوقين, الهيئة الوطنية لشؤون المسنين, المؤسسة العامة للإسكان, الصندوق المركزي للشؤون الاجتماعية.
- Minister: Haneen Sayed (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للشؤون الاجتماعية — DG of Social Affairs | Judge Hala Hani al-Mawla (القاضية هالة المولى) | appointed 2026-05-22 | https://www.mtv.com.lb/news/1698183 ; https://www.akhbaralyawm.com/news/545815 |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| المجلس الأعلى للطفولة — Higher Council for Childhood | council attached to MoSA (secretariat inside ministry) | Decree 11/1994 — UNVERIFIED | Secretary general **UNVERIFIED** | |
| الهيئة الوطنية لشؤون المعوقين — National Council for Disability Affairs | consultative body under Law 220/2000, half elected by disability associations | Law 220 of 29-5-2000 | dormant 2018–2025; elections held 2026-01 (results announced 2026-01-13) | Elnashra 2026-01-13; annahar 2026-02-09 |
| الهيئة الوطنية لشؤون المسنين — National Commission for the Elderly | consultative | Decree 1999 — UNVERIFIED | UNVERIFIED | |
| المؤسسة العامة للإسكان — Public Housing Institution (PHI) | public institution; CSB chart lists it among bodies attached to MoSA (tutelage historically MoSA) | Law 539 of 24-7-1996 | Chairman-DG **UNVERIFIED** (Rony Lahoud held it 2014–2024) | https://www.pch.gov.lb (untested) |
| الصندوق المركزي للشؤون الاجتماعية — Central Fund for Social Affairs | fund | UNVERIFIED | | |

### 1.11 Ministry of Public Health — وزارة الصحة العامة — Ministère de la Santé publique

- Site: https://www.moph.gov.lb (200). Legal basis: Decree 8377 of 30-12-1961 (organisation) — UNVERIFIED. CSB chart (10 pages): one DG (المديرية العامة للصحة) with directorates.
- Minister: Rakan Nasreddine (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للصحة — DG of Public Health | Dr Wiam Abou Hamdan (وئام أبو حمدان) | appointed 2026-05-22 | https://www.mtv.com.lb/news/1698183 ; https://www.moph.gov.lb (site lists him as DG) |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| مستشفى رفيق الحريري الجامعي — Rafik Hariri University Hospital | public institution (hospital) under MoPH tutelage | Law 544 of 1996 (public hospitals as public institutions) — UNVERIFIED number | DG Dr Mohammad Salim Zaatari (محمد سليم زعتري), appointed 2025-08-13 | https://www.lebanondebate.com/article/732036 ; https://rhuh.gov.lb |
| المستشفيات الحكومية (نحو 29) — governmental hospitals | each a public institution with a board and chair; tutelage MoPH | Law 544/1996 | ~29 board chair/member posts vacant; OMSAR plans to merge chair and DG posts and appoint in one batch (2026-09) | https://kataeb.org/articles/sl-241234 ; MoPH list https://www.moph.gov.lb/ar/Pages/3/630/ |
| الهيئة اللبنانية لسلامة الغذاء — Lebanese Food Safety Authority | authority created by Law 35 of 2015 (attached to the PCM, with MoPH/MoA/MoET representation) — *paper-only 2015–2025* | Law 35 of 24-11-2015 | board appointed 2025-10-23/24; president Dr Elie Awad (pcm PDF 2026-08-07); member Charles Abboud resigned 2026-08-07 | Elnashra 2025-10-24 (Abou Faour); pcm PDF 2026-08-07 |
| الهيئة الوطنية لوهب وزرع الأعضاء — National Organ Donation and Transplant Authority | authority (linked from MoPH site) | UNVERIFIED | | https://www.moph.gov.lb |

### 1.12 Ministry of Labour — وزارة العمل — Ministère du Travail

- Site: https://www.labor.gov.lb (200). Legal basis: Decree 8352 of 30-12-1961 (organisation, still in force with amendments); history https://www.labor.gov.lb/AboutUsPage.aspx?type=2 . CSB chart: one DG; attached: الصندوق الوطني للضمان الاجتماعي, المؤسسة الوطنية للاستخدام.
- Minister: Mohammad Haidar (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة لوزارة العمل — DG of the Ministry of Labour | **vacant**; recruitment call via the official platform closed 2026-08-16 | vacant | https://kataeb.org/articles/sl-241234 |

| Attached body | Legal form | Creating text | Head / board (dated) | Site |
|---|---|---|---|---|
| الصندوق الوطني للضمان الاجتماعي — National Social Security Fund (NSSF) | autonomous public institution with tripartite board (26 members), tutelage Ministry of Labour | Social Security Law, Decree 13955 of 26-9-1963 | DG Dr Mohammad Karaki (in post since 2010s; 2026 status: still DG per press 2026-08-17); board appointed by decree approved 2026-08-07 (state delegates Maroun Sikaly, Bassem Halim Ghanem; employers incl. Mounir Bissat, Asaad Mirza, Patricia Hachem; workers Bechara Asmar et al.); board elected Bechara al-Asmar chairman, Bassam Alliq vice-chairman, Maroun Sikaly secretary on 2026-08-17 | pcm PDF 2026-08-07; https://www.annahar.com 2026-08-17 "انتخاب بشارة الأسمر رئيساً لمجلس إدارة الضمان"; almarkazia 2026-08-17; https://www.cnss.gov.lb (200) |
| المؤسسة الوطنية للاستخدام — National Employment Office (NEO) | public institution, tutelage MoL | Decree-Law 80 of 27-6-1977 | Chair/DG **UNVERIFIED**; largely inactive (Legal Agenda 2019); worker representatives named by CGTL 2025-09-04 | https://www.neo.gov.lb (untested); almarkazia 2025-09-04 |
| مجالس العمل التحكيمية — Labour Arbitration Councils | tripartite tribunals under the Labour Code | Labour Code 1946 | | |

### 1.13 Ministry of Education and Higher Education — وزارة التربية والتعليم العالي — Ministère de l'Éducation et de l'Enseignement supérieur

- Site: https://www.mehe.gov.lb (200). Legal basis: Law 247 of 7-8-2000 (rename; separation of Youth and Sports; absorption of the Ministry of Vocational and Technical Education): https://ar.wikipedia.org/wiki/وزارة_التربية_والتعليم_العالي_(لبنان) ; legal history page https://www.mehe.gov.lb/ar (التاريخ القانوني). CSB chart (5 pages): three DGs (Education; Higher Education — "still without an organisation chart"; Vocational and Technical Education) plus المجلس الأعلى للتعليم المهني والتقني and its internal fund (Law 211 of 2-4-1993).
- Minister: Rima Karami (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للتربية — DG of Education | Imad Achkar (عماد الأشقر), assigned (بالتكليف) since 2022-02-12; the 2025-02 Al-Akhbar report "إلغاء مخالفة تكليف الأشقر" concerns that assignment | acting — 2026 status **UNVERIFIED** | Elnashra 2022-02-12; Al-Akhbar 2025-02-27 |
| المديرية العامة للتعليم العالي — DG of Higher Education | Dr Mazen Abdel Fattah al-Khatib (مازن الخطيب), seconded from the Lebanese University | appointed 2026-07-09 ("مركز شاغر") | pcm PDF 2026-07-09; Al-Akhbar 2026-07-10 |
| المديرية العامة للتعليم المهني والتقني — DG of Vocational and Technical Education | **UNVERIFIED** | | CSB chart |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| المركز التربوي للبحوث والإنماء — Centre for Educational Research and Development (CERD) | public institution, tutelage MEHE (CSB chart 2023: reports to minister; advisory body of unit heads) | Decree 2356 of 28-12-1971 | President Prof. Hiam Ishak (هيام إسحاق), appointed 2026-09-17 (first announced 2026-08-07) | https://www.lebanondebate.com/article/879687 ; https://www.lebanondebate.com/article/866648 ; https://www.crdp.org (200) |
| الجامعة اللبنانية — Lebanese University | public institution of higher education with academic autonomy, tutelage MEHE | Law of 26-2-1953 (Decree 25/1953) and Law 75 of 26-12-1967 (organisation); Law 6/70 of 1970 | President: term is 5 years renewable once; the Constitutional Council on 2026-09-03 annulled the 6-month exceptional extension of the incumbent's term, leaving the appointment to the cabinet (incumbent Prof. Bassam Badran, appointed 2021 — name UNVERIFIED) | https://www.lebanondebate.com/article/875306 ; https://www.ul.edu.lb (200); https://ar.wikipedia.org/wiki/الجامعة_اللبنانية |
| المجلس الأعلى للتعليم المهني والتقني — Higher Council for Vocational and Technical Education | council attached to MEHE | Law 211 of 2-4-1993 | | CSB chart |
| مجلس التعليم العالي — Council of Higher Education | council chaired by the minister (private universities licensing) | Law 285 of 30-4-2014 | | |

### 1.14 Ministry of Culture — وزارة الثقافة — Ministère de la Culture

- Site: http://culture.gov.lb (200). Legal basis: Law 35 of 16-10-2008 (organisation of the Ministry of Culture) and Law 36 of 16-10-2008 (public institutions attached to the ministry): http://culture.gov.lb (القوانين واللوائح). Structure (two DGs): http://culture.gov.lb/ar/The-Ministry/Structure . CSB chart (4 pages) adds صندوق دعم الأنشطة والصناعات الثقافية, الصندوق الخاص بالآثار والمنشآت التراثية, المكتبة الوطنية, الهيئة العامة للمتاحف.
- Minister: Ghassan Salamé (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للشؤون الثقافية — DG of Cultural Affairs | **UNVERIFIED** | | structure page |
| المديرية العامة للآثار — DG of Antiquities | **UNVERIFIED** (Sarkis Khoury held the post from 2009) | | structure page |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| المكتبة الوطنية — National Library | public institution (Law 36/2008) | Law 36 of 2008 | UNVERIFIED | http://culture.gov.lb |
| المعهد الوطني العالي للموسيقى — Conservatoire national supérieur de musique | public institution (Law 36/2008) | Law 36 of 2008 | President **UNVERIFIED** | |
| المتحف الوطني / الهيئة العامة للمتاحف — National Museum / General Authority for Museums | public institution (Law 36/2008) | Law 36 of 2008 | board appointed by cabinet 2025-11-27 (An-Nahar headline "مجلس الوزراء يعيّن مجلس إدارة المتاحف") — names UNVERIFIED | |
| المركز الدولي لعلوم الإنسان (جبيل) — Centre International des Sciences de l'Homme | public institution / UNESCO-linked centre | Law 36/2008 | UNVERIFIED | |
| المكتبة الوطنية في بعقلين; اللجنة الوطنية اللبنانية للتربية والعلم والثقافة (اليونسكو); قصر الأونيسكو | listed as "الجهات التابعة" on the ministry site | | | http://culture.gov.lb |
| صندوق التعاضد الموحد للفنانين — Artists' Unified Mutual Fund | fund (Decree 7535 of 15-2-2012) | Law 56 of 27-12-2008 | | http://culture.gov.lb |

### 1.15 Ministry of Agriculture — وزارة الزراعة — Ministère de l'Agriculture

- Site: https://www.agriculture.gov.lb (200). Legal basis: Decree-Law 31 of 18-1-1955 (functions); Decree 5246 of 5-6-1994 (organisation, in force): https://ar.wikipedia.org/wiki/وزارة_الزراعة_(لبنان) ; DG of Cooperatives merged into the ministry by Law 247/2000 (https://www.agriculture.gov.lb , التعاونيات page). CSB chart (9 pages): two DGs (Agriculture; Cooperatives), attached هيئة التنسيق والتخطيط, المجلس الزراعي الأعلى (advisory), المؤسسة العامة للزيت والزيتون and المؤسسة العامة للزراعات البديلة (both *paper-only*), plus LARI and the Green Plan.
- Minister: Nizar Hani (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للزراعة — DG of Agriculture | Eng. Louis Lahoud (لويس لحّود) | in post since 2003-09-04 (ministry site, live 2026-09-18) | https://www.agriculture.gov.lb (المدير العام page) |
| المديرية العامة للتعاونيات — DG of Cooperatives | **UNVERIFIED** | | CSB chart |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| مصلحة الأبحاث العلمية الزراعية — Lebanese Agricultural Research Institute (LARI) | public institution; board chaired by the DG of Agriculture (CSB chart 2023) | Decree 16766 of 1964 — year UNVERIFIED | President/DG **UNVERIFIED** (Michel Afram held it until 2024) | http://www.lari.gov.lb (bot check) |
| المشروع الأخضر — Green Plan | public institution, tutelage MoA | Law of 1963 — UNVERIFIED | President **UNVERIFIED** | |
| المؤسسة العامة للزيت والزيتون; المؤسسة العامة للزراعات البديلة | *paper-only* public institutions listed on the CSB chart | | never constituted (UNVERIFIED) | |
| مكتب الحبوب والشمندر السكري | see 1.2 (Economy) | | | |

### 1.16 Ministry of Environment — وزارة البيئة — Ministère de l'Environnement

- Site: https://www.moe.gov.lb (bot check; exists). Legal basis: Law 216 of 2-4-1993 (creation); Law 690 of 26-8-2005 (functions and organisation) — a draft amendment to Law 690 was approved by cabinet 2026-08-07 (pcm PDF 2026-08-07): https://ar.wikipedia.org/wiki/وزارة_البيئة_(لبنان) . CSB chart: one DG; attached councils: المجلس الأعلى للصيد البري, المجلس الوطني للبيئة, المجلس الوطني للمقالع.
- Minister: Tamara el-Zein (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للبيئة — DG of Environment | **UNVERIFIED** (Berj Hatjian retired 2019; post reported vacant/acting since) | vacant/acting UNVERIFIED | raw/ld search "مدير عام البيئة" |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| الهيئة الوطنية لإدارة النفايات الصلبة — National Solid Waste Management Authority | authority under art. 28 of Law 80/2018 as amended by Law 38 of 5-1-2026 | Law 80 of 10-10-2018; Law 38/2026; implementing decree amended by cabinet 2026-07-09 | members appointed by cabinet 2026-01-30 (names UNVERIFIED) | https://www.lebanondebate.com/article/786051 ; pcm PDF 2026-07-09 |
| المجلس الوطني للبيئة; المجلس الأعلى للصيد البري; المجلس الوطني للمقالع | advisory councils chaired by the minister | Law 690/2005; Hunting Law 580/2004; Quarries decree 8803/2002 | | CSB chart |

### 1.17 Ministry of Industry — وزارة الصناعة — Ministère de l'Industrie

- Site: https://www.industry.gov.lb (bot check; exists). Legal basis: Law 642 of 2-6-1997 (creation of the Ministry of Industry) — UNVERIFIED number; structure https://ar.wikipedia.org/wiki/وزارة_الصناعة_(لبنان) . CSB chart: one DG; attached هيئة إنشاء وإدارة مراكز التجمع الصناعي, مؤسسة المواصفات والمقاييس اللبنانية (LIBNOR), المجلس اللبناني للاعتماد (COLIBAC).
- Minister: Joe Issa el-Khoury (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للصناعة — DG of Industry | **UNVERIFIED** (post exists and is occupied: the DG chairs COLIBAC ex officio per the 2026-08-07 decision) | | pcm PDF 2026-08-07 |

| Attached body | Legal form | Creating text | Head / board (dated) | Site |
|---|---|---|---|---|
| مؤسسة المقاييس والمواصفات اللبنانية (ليبنور) — LIBNOR | public institution, tutelage Ministry of Industry (CSB chart 2023) | Law of 23-7-1962 | Chairman and DG **UNVERIFIED** (site has "Message from Chairman" and "Message from Director General"; representative on COLIBAC Lana Dergham) | http://www.libnor.gov.lb (200) |
| معهد البحوث الصناعية — Industrial Research Institute (IRI) | non-profit institution under tutelage of the ministry | Decree 10059 of 1997 — UNVERIFIED | DG **UNVERIFIED** (Bassam Frenn represents IRI on COLIBAC) | https://www.iri.org.lb |
| المجلس اللبناني للاعتماد — Lebanese Accreditation Council (COLIBAC) | council under Decree 16350 of 10-2-2006 (a request to abolish it was withdrawn 2026-07-09) | Decree 16350/2006 | new board 2026-08-07 (3 years): DG Industry (chair), DG Economy (vice), DG Agriculture, Bassel al-Ayoubi, Bassam Frenn, Maha Eid, Lana Dergham, Naji Mezher, Chaker Saab, Elie Rizk, Nesrine Ghaddar, Elie Awad | pcm PDF 2026-07-09; pcm PDF 2026-08-07 |
| هيئة إنشاء وإدارة مراكز التجمع الصناعي — Industrial Zones Authority | *paper-only* (CSB chart) | UNVERIFIED | | |

### 1.18 Ministry of Information — وزارة الإعلام — Ministère de l'Information

- Site: https://www.ministryinfo.gov.lb (200). Legal basis: law implemented by Decree 7276 of 7-8-1961 (organisation) and Decree 8254 of 20-12-1961 (staffing); reorganised by Decree-Law 25 of 26-4-1983 (CSB chart, notes 1–3, which presents both structures because the 1983 implementing decrees were never issued). CSB chart: one DG (المديرية العامة للإعلام) with executive and advisory councils; المجلس الوطني للإعلام المرئي والمسموع shown as related.
- Minister: Paul Morcos (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للإعلام — DG of Information | **UNVERIFIED** (Hassan Falha has held the post since 2011) | | CSB chart; raw/ld search |
| مدير الوكالة الوطنية للإعلام; مدير إذاعة لبنان | directors of the NNA and Radio Lebanon (units of the ministry) | UNVERIFIED | |

| Attached body | Legal form | Creating text | Head / board (dated) | Site |
|---|---|---|---|---|
| الوكالة الوطنية للإعلام — National News Agency (NNA) | directorate of the ministry (not a legal person), founded 1961 | Decree 7276/1961 | Director **UNVERIFIED** | https://www.nna-leb.gov.lb (200); https://ar.wikipedia.org/wiki/الوكالة_الوطنية_للإعلام |
| إذاعة لبنان — Radio Lebanon | directorate of the ministry | Decree 7276/1961 | UNVERIFIED | |
| تلفزيون لبنان ش.م.ل. — Télé Liban SAL | state-owned company (100% state since 1996), tutelage Ministry of Information; created 1977 by merger of CLT and Télé-Orient | Decree 1977 (merger) — UNVERIFIED; Law 382/1994 (audiovisual) | Chairwoman-DG Elissar Elias Naddaf (اليسار نداف) with members Jinan Mallat, Charles Saba, Mohammad Nemr Mustafa, Ali Ibrahim Kassem, Rima Khaddaj — appointed by cabinet 2025-07-11 after a public call (call closed 2025-06-06; https://en.wikipedia.org/wiki/T%C3%A9l%C3%A9_Liban); staff pay freeze discussed 2026-04-30 | https://www.lebanondebate.com/article/721341 ; https://www.lebanondebate.com/article/709318 ; https://www.teleliban.com.lb (200) |
| المجلس الوطني للإعلام المرئي والمسموع — National Audiovisual Council | advisory/regulatory council of 10 members (5 elected by parliament, 5 by cabinet), Law 382/1994 | Law 382 of 4-11-1994 | mandate **expired**; listed among unfilled bodies 2026-09 ("المجلس الوطني للإعلام") | https://kataeb.org/articles/sl-241234 ; akhbaralyawm "كهرباء لبنان والوطني للإعلام وإنترا منتهية الولاية" |

### 1.19 Ministry of Tourism — وزارة السياحة — Ministère du Tourisme

- Site: https://mot.gov.lb (200). Legal basis: created 1966 (Decree-Law of 1966, number UNVERIFIED), preceded by the Commissariat Général du Tourisme (1948) and the Conseil National du Tourisme (1962): https://ar.wikipedia.org/wiki/وزارة_السياحة_(لبنان) ; Law 215 of 2-4-1993 created the service for touristic exploitation of archaeological sites and museums (CSB chart). CSB chart: one DG (المديرية العامة للشؤون السياحية) with tourist police office and tourism offices abroad.
- Minister: Laura Khazen Lahoud (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للشؤون السياحية — DG of Tourism | **acting/assigned since July 2023**; holder name UNVERIFIED | acting | https://kataeb.org/articles/sl-241234 |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| مغارة جعيتا — Jeita Grotto | state site under MoT, exploited by concession (minister's decision 2025-01-14 on exploitation) | | | kataeb.org 2025-01-14 |
| كازينو لبنان — Casino du Liban | SAL with state-controlled shareholding via Intra Investment; licence/tutelage via MoT & MoF | Decree 1957 (licence) — see section 3 | Chairman-DG Charles Ghostine (شارل غسطين) since 2025 (replacing Roland Khoury) | An-Nahar 2026-01-15 https://www.annahar.com/lebanon/269695 ; Al-Akhbar "القصر «يقيل» خوري ويعيّن غسطين" (2025) |

### 1.20 Ministry of Youth and Sports — وزارة الشباب والرياضة — Ministère de la Jeunesse et des Sports

- Site: https://minijes.gov.lb (200 on fetch; www. host behind bot check). Legal basis: Law 247 of 7-8-2000 (creation by separation from Education); Law 629 of 2004 (organisation of the ministry); Decree 3196 of 7-4-2016 (staffing); Decree 4481 of 27-10-2016 (sports/youth/scout movement); Decree 16681 of 30-3-2006 (Public Institution for Sports, Scout and Youth Facilities): https://minijes.gov.lb (التشريعات). CSB chart: one DG.
- Minister: Nora Bayrakdarian (since 2025-02-08).

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة للشباب والرياضة — DG of Youth and Sports | Fadia Hallal (فاديا حلال), assigned as acting DG by the minister 2024-03-27 | acting since 2024 (OMSAR list 2026-09) | Elnashra 2024-03-27; https://kataeb.org/articles/sl-241234 |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| المؤسسة العامة للمنشآت الرياضية والكشفية والشبابية — Public Institution for Sports, Scout and Youth Facilities | public institution, tutelage MYS (CSB chart 2023: manages all state sports facilities incl. Camille Chamoun Sports City) | Decree 16681 of 30-3-2006 | DG Naji Hammoud (ناجي حمود), appointed by cabinet 2026-04-02 | Lebanon al-Kabir 2026-04-02 "الحكومة تعين ناجي حمود مديراً عاماً للمنشآت الرياضية"; Al-Akhbar 2026-04-02 https://www.al-akhbar.com/news/lebanon/886677 |
| مدينة كميل شمعون الرياضية — Camille Chamoun Sports City | facility managed by the institution above (reopened May 2025) | | | An-Nahar 2025-05-22 https://www.annahar.com/sports/217838 |
| اللجنة الأولمبية اللبنانية — Lebanese Olympic Committee | private-law federation (not a state body) | | | |

### 1.21 Ministry of the Displaced — وزارة المهجرين — Ministère des Déplacés

- Site: https://www.ministryofdisplaced.gov.lb (bot check; exists). Legal basis: Law 190 of 4-1-1993 (creation) and Law 193 of 1993 (Central Fund for the Displaced); financing laws 242, 333, 362; decrees 3406, 3370, 3410, 8672, 9934 (list on the ministry site, القوانين والمراسيم). CSB chart (2026-04 PDF, "وزارة شؤون المهجرين"): one DG.
- Minister: Kamal Shehadi (since 2025-02-08). In 2025-09 he announced a plan to wind down the ministry and replace it with a Ministry of Technology (imlebanon 2025-09-12 "شحادة يقفل وزارة المهجرين ويفتح وزارة التكنولوجيا"); no abolition law identified as of 2026-09-18.

| DG post | Holder | Status | Source |
|---|---|---|---|
| المديرية العامة لوزارة المهجرين — DG of the Ministry of the Displaced | **UNVERIFIED** (press search term "أحمد محمود"; a DG met the NSSF director 2024-06-10) | UNVERIFIED | Sawt Beirut 2024-06-10 |

| Attached body | Legal form | Creating text | Head (dated) | Site |
|---|---|---|---|---|
| الصندوق المركزي للمهجرين — Central Fund for the Displaced | public institution with a 9-member non-full-time board, chaired ex officio by the fund's president; tutelage PCM (CSB chart 2023: "سلطة الوصاية: رئاسة مجلس الوزراء") | Law 193 of 1993 | President **UNVERIFIED** | https://www.cfd.gov.lb (bot check) |

### 1.22 Office of the Minister of State for Administrative Development (OMSAR) — مكتب وزير الدولة لشؤون التنمية الإدارية — Ministère d'État pour le Développement administratif

- Site: https://www.omsar.gov.lb (403 Cloudflare; exists). Legal basis: created 1993 as a minister-of-state office financed by donor projects (Decree of 1993 — UNVERIFIED); it has no organisation law, no DG and no cadre; "وزارة التنمية الإدارية" is used in press. Functions: administrative reform, e-government ("دولتي" platform launched 2026-07-14, https://elsiyasa.com/article/422791), World Bank USD 150 m digital-transformation loan (2026-07-24, Elnashra 1792199), management of the grade-1 appointments pipeline and the "national programme for governance of boards" (2026-06-04).
- Minister: Fadi Makki (since 2025-02-08). Turf dispute with the Minister of State for IT & AI over digital transformation left unresolved by cabinet (Al-Akhbar 2026-07-24 "الحكومة لا تحسم الخلاف بين شحادة ومكي").
- Attached: none (project units only). The National Institute of Administration (ENA, المعهد الوطني للإدارة) is under CSB tutelage, not OMSAR (CSB chart).

### 1.23 Minister of State for Information Technology and Artificial Intelligence — وزير دولة لشؤون تكنولوجيا المعلومات والذكاء الاصطناعي

- No ministry, no site, no cadre; the portfolio is held by Kamal Shehadi concurrently with the Displaced (section 0). Parliament's IT committee approved an amended government bill creating a "وزارة التكنولوجيا والذكاء الاصطناعي" on 2026-01-13 (Elnashra; Sawt Beirut) and the plenary of 2026-07-15 passed it according to press (Al-Akhbar 2026-07-15 "وزارة الذكاء الاصطناعي... غباء إداري"; Legal Agenda 2026-07-15 session report; An-Nahar 2026-07-20). President Aoun returned four laws of that session to parliament on 2026-08-07 (An-Nahar headline "جوزف عون يطلب إعادة النظر في 4 قوانين") — whether the technology-ministry law is among them, and whether it was published in the Gazette, is **UNVERIFIED**; as of 2026-09-18 pcm.gov.lb still lists only the minister-of-state title and no such ministry appears in the cabinet list, so it should be modelled as *not yet constituted*.

### 1.24 Deputy Prime Minister — نائب رئيس مجلس الوزراء

- Tarek Mitri, no portfolio, no administration. Assigned political files (Syrian relations, refugees, documentation of Israeli violations): Sawt al-Emarat 2026-05-09; Al-Akhbar 2026-07-21.

## 2. Independent authorities, regulators, councils and funds reporting to the Council of Ministers or the Presidency

Sources for this section unless stated: the PCM list of attached bodies https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=4211 ; CSB public-institution charts https://www.csb.gov.lb/ar/الهيكليات/ ; pcm PDFs 2026-07-09 and 2026-08-07 (section 1); institution websites as fetched 2026-09-18. "Constituted" = board/members in office on 2026-09-18.

### 2.1 Monetary and financial regulators

| Body (AR / EN) | Creating text | Governance | Head and board as of 2026-09-18 | Constituted? | Site |
|---|---|---|---|---|---|
| مصرف لبنان — Banque du Liban (BdL) | Code of Money and Credit, Decree 13513 of 1-8-1963 | Governor (6-year renewable term, decree in cabinet on MoF proposal) + 4 vice-governors (5 years, decree in cabinet after consulting the governor) + Central Council (governor, 4 VGs, DG Finance, DG Economy ex officio) | Governor Karim Souaid since 2025-03-27 (cabinet vote 17/24); DG Finance George Maarawi and DG Economy Mohammad Abou Haidar sit ex officio; the four vice-governors: **UNVERIFIED** whether the 2020 cohort (Wassim Mansouri, Bachir Yakzan, Salim Chahine, Alexandre Mouradian) was replaced in 2025–26 | yes | https://www.bdl.gov.lb (403 Cloudflare); https://en.wikipedia.org/wiki/Banque_du_Liban ; https://ar.wikipedia.org/wiki/كريم_سعيد |
| المجلس المركزي لمصرف لبنان — Central Council | same | see above | as above | yes | |
| الهيئة المصرفية العليا — Higher Banking Commission | Law 28/67 art. 10 | chaired by the governor; a VG, DG Finance, a senior judge, a banking expert, BCC chair | chair Souaid; judge Souheir al-Harake (per SIC page) | yes | https://www.sic.gov.lb/en/about-us |
| لجنة الرقابة على المصارف — Banking Control Commission (BCC) | Law 28/67 of 9-5-1967 | 5 members appointed by cabinet for 5 years (chair proposed by MoF) | Chairman Dr Mazen Soueid (2025–present per CMA and SIC pages); other members UNVERIFIED | yes | https://bccl.gov.lb (200); https://www.cma.gov.lb/about-cma/ |
| هيئة الأسواق المالية — Capital Markets Authority (CMA) | Law 161 of 17-8-2011 | 7-member board chaired by the BdL governor: 3 full-time executive members + BCC chair + DG Finance + DG Economy | Chairman Karim Souaid; executive members appointed 2025-11-13: Mahmoud Jebaee (vice-chairman), Ghassan Abou Adal, Zeina Abdel Samad al-Mohtar; members Mazen Soueid, Mohammad Abou Haidar, George Maarawi | yes | https://www.cma.gov.lb (200); https://www.cma.gov.lb/about-cma/ (board list live 2026-09-18) |
| هيئة التحقيق الخاصة — Special Investigation Commission (SIC, financial intelligence unit) | Law 318 of 20-4-2001, now Law 44 of 24-11-2015 (AML/CFT) | chaired by the governor; members BCC chair, the judge of the Higher Banking Commission, a professional member | Chairman Karim Souaid; Mazen Soueid; Judge Souheir al-Harake; 4th member UNVERIFIED | yes | https://www.sic.gov.lb (200), https://sic.gov.lb/en/about-us |
| المؤسسة الوطنية لضمان الودائع — National Deposit Guarantee Institution | Law 28/67 | mixed public-private institution; board chaired by a BdL vice-governor | UNVERIFIED | yes (legacy) | |
| لجنة مراقبة هيئات الضمان — Insurance Control Commission | Decree-Law 9812/1968 | inside Ministry of Economy | see 1.2 | partly (new president 2025-26, UNVERIFIED) | https://www.icc.gov.lb |
| Deposit-recovery framework 2025–26 | Bank reform/resolution law: cabinet draft 2025, passed by parliament in 2025 and amended in 2026 (final consolidated text published by Al-Modon 2026-08-17); "قانون الفجوة المالية" (financial gap / deposit recovery law): approved by cabinet 2025-12-26 (An-Nahar), sent to parliament, still in the Finance committee — not enacted as of 2026-09-18 (IMF mission conclusion 2026-09-18; Governor Souaid on 2026-09-08 expected 6–8 more months) | no new body created yet; the drafts assign roles to BdL, BCC and a deposit-recovery fund (UNVERIFIED design) | — | *not constituted* | https://www.almodon.com (2026-08-17); https://www.annahar.com (2026-09-18 IMF mission); Elnashra 2026-09-08 |
| الصندوق السيادي — Sovereign Wealth Fund | draft law debated 2022–23 (Legal Agenda 2023-08-15 "الصندوق السيادي للنفط: بيع الوهم"); Al-Akhbar 2026 headline "الصندوق السيادي يرث «حصّة القصر»" refers to a 2026 draft transferring the state's Casino/Intra stakes to a sovereign fund | UNVERIFIED whether any law was promulgated | — | *paper-only / draft* | raw/gn_swf |

### 2.2 Sector regulators

| Body | Creating text | Governance | Head / board (dated) | Constituted? | Site |
|---|---|---|---|---|---|
| الهيئة المنظمة للاتصالات — TRA | Law 431/2002 | chair + 4 full-time members, 5 years non-renewable, appointed by cabinet on the minister's proposal | Chairwoman-CEO Dr Jenny Gemayel + 4 (2025) | yes (dormant 2012–2025) | https://www.tra.gov.lb/Board-Members |
| هيئة تنظيم قطاع الكهرباء — Electricity Regulatory Authority | Law 462/2002 | chair + members appointed by cabinet | President Ziad Samakieh (2026-07-09); members 2025–26 | yes, first time (paper-only 2002–2025) | pcm PDF 2026-07-09 |
| هيئة إدارة قطاع البترول — LPA | Law 132/2010 | 6-member board, 6 years | new board 2026-06-25 (names UNVERIFIED) | yes | https://www.lpa.gov.lb |
| الهيئة الوطنية للمنافسة — National Competition Authority | Competition Law 2022 (number UNVERIFIED) | president, vice-president, 5 council members (4 years), government commissioner | Rola Akoum (pres.), Stephanie Saliba (VP), Mohammad Abou Haidar (govt commissioner) 2026-08-07; members 2026-09-10 | yes, first time | pcm PDF 2026-08-07; https://www.lebanondebate.com/article/877452 |
| هيئة الشراء العام — Public Procurement Authority | Public Procurement Law 244 of 19-7-2021 | full-time president + 4 full-time members, 5 years | President Jean Salim Ellieh, members Omar al-Barraj, Mohammad Marwan Seifeddine, Rima Bazzi, Rana Rizkallah (2026-08-07); first annual report announced for early 2026 | yes | pcm PDF 2026-08-07; https://www.ppa.gov.lb (200) |
| الهيئة الوطنية لإدارة النفايات الصلبة | Law 80/2018 as amended by Law 38/2026 | appointed by cabinet | members appointed 2026-01-30 (UNVERIFIED names) | yes | https://www.lebanondebate.com/article/786051 |
| الهيئة الناظمة لزراعة القنب الهندي | Law 178/2020 | DG + board | acting DG Makkieh (2026-06-04) | partly | https://www.lebanondebate.com/article/844185 |
| الهيئة اللبنانية لسلامة الغذاء — Food Safety Authority | Law 35/2015 | board appointed by cabinet | president Dr Elie Awad (board 2025-10-24) | yes, first time | pcm PDF 2026-08-07 |
| المجلس الوطني للإعلام المرئي والمسموع — National Audiovisual Council | Law 382/1994 | 10 members (5 parliament, 5 cabinet) | mandate expired; not renewed as of 2026-09 | *expired* | https://kataeb.org/articles/sl-241234 |
| هيئة الطيران المدني (الهيئة العامة للطيران المدني) — Civil Aviation Authority | Law 481 of 12-12-2002 (creating an authority to replace the DGCA) | board + DG | *paper-only since 2002*: a call for a DG, board member and government commissioner was run in 2025 (Elnashra 2025-05-29) but no appointment confirmed; MP Hassan Khalil questioned the government on its powers 2026-07-21 | no (DGCA still operates) | Elnashra 2025-05-29; Al-Akhbar 2026-07-21 |

### 2.3 Oversight and control bodies attached to the PCM

| Body | Creating text | Governance | Head (dated) | Site |
|---|---|---|---|---|
| مجلس الخدمة المدنية — Civil Service Board | Decree-Law 114 of 12-6-1959 | president + members (judges/senior officials) | President: a woman ("رئيسة مجلس الخدمة المدنية", WAFA 2026-08-02) — Nisrine Machmouchi (appointed 2020) **name UNVERIFIED** | https://www.csb.gov.lb (200) |
| التفتيش المركزي — Central Inspection | Decree-Law 115 of 12-6-1959 | president + inspectorates | President Judge Georges Attieh (swore in 18 inspectors 2026-02-24, kataeb.org) | https://www.cib.gov.lb (200) |
| الهيئة العليا للتأديب — Higher Disciplinary Authority | Decree-Law 112/1959 (staff regulations) | president (judge) + members | UNVERIFIED | CSB chart |
| ديوان المحاسبة — Court of Audit | Decree-Law 82 of 16-9-1983 | administrative court; president + chamber presidents; prosecutor | President **UNVERIFIED** (Judge Mohammad Badran since 2020 per earlier press); prosecutor Judge Fawzi Khamis (MTV 2026-03-31); Judge Wassim Abou Saad appointed chamber president 2026-02-16 | https://www.coa.gov.lb (200) |
| المجلس الدستوري — Constitutional Council | Law 250/1993 | 10 members (5 parliament, 5 cabinet), 6 years | members' term expired Aug 2025; council still issuing decisions (2026-09-03 ruling on LU law, president Judge Tannous Mechleb) — renewal pending | https://www.lebanondebate.com/article/875306 ; https://kataeb.org/articles/sl-241234 |
| الهيئة الوطنية لمكافحة الفساد — National Anti-Corruption Commission | Law 175 of 8-5-2020 | 6 full-time members, 6 years | first commission appointed by Decree 8742 of 28-1-2022; president Judge Claude Karam (UNVERIFIED name; bio on site matches) | https://nacc.gov.lb/about-us/ (200) |
| هيئة الإشراف على الانتخابات — Supervisory Commission for Elections | Electoral Law 44 of 17-6-2017 | 11 members appointed by cabinet | appointed 2025-12-12 (MTV "تعيين هيئة الإشراف على الانتخابات... مَن تضم؟"); names UNVERIFIED; elections postponed to 2028 | https://elections.gov.lb (200); janoubia 2025-12-12 |
| الهيئة الوطنية لحقوق الإنسان (المتضمنة لجنة الوقاية من التعذيب) — National Human Rights Commission | Law 62 of 27-10-2016 | 10 members, 6 years, by decree | constituted by decree 2018; chair UNVERIFIED for 2026 | https://nhrclb.org (200); https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=13560 |
| الهيئة الوطنية لشؤون المرأة اللبنانية — National Commission for Lebanese Women | Law 720 of 5-11-1998 | 24 members by decree of the PCM, chaired by custom by the First Lady | President Neemat Aoun (first meeting 2025-03-26) | https://nclw.gov.lb (200); An-Nahar 2025-03-26 |
| الهيئة الوطنية للمفقودين والمخفيين قسراً | Law 105/2018 | 10 members | chair Judge Joseph Samaha (2025) | see 1.6 |

### 2.4 Development, relief and economic councils/funds attached to the PCM

| Body | Creating text | Governance | Head (dated) | Site |
|---|---|---|---|---|
| مجلس الإنماء والإعمار — Council for Development and Reconstruction (CDR) | Decree-Law 5 of 31-1-1977 | president + board + secretary general + government commissioner; reports to the PM | President Mohammad Kabbani (2025-05-14, replacing Hassan Zaiter); Secretary General Ghassan Khairallah (2025-05-29); board members selected through the 2025 call (634 applicants) — names UNVERIFIED; dispute with the government commissioner Dec 2025 | https://www.cdr.gov.lb (403); https://www.lebanondebate.com/article/703056 ; Elnashra 2025-05-29; https://en.wikipedia.org/wiki/Council_for_Development_and_Reconstruction |
| مجلس الجنوب — Council of the South | Law of 1970 (Council of the South) — UNVERIFIED number | board chaired by a president-DG; tutelage PCM (CSB chart) | President Hashem Haidar (in post 2026-05-17, Al-Anbaa interview) | Elnashra 2026-04-18; CSB chart |
| الهيئة العليا للإغاثة — Higher Relief Council/Commission | Decree 1976 (Decree-Law 8/1976?) — UNVERIFIED | chaired by the PM; secretary general (army officer) | Secretary General Brig. Gen. Bassam Nabulsi (2026-04-10, PM's office) | https://www.annahar.com 2026-04-10 ("الرئيس سلام يوعز إلى الأمين العام للهيئة العليا للإغاثة العميد بسام نابلسي") |
| الصندوق المركزي للمهجرين — Central Fund for the Displaced | Law 193/1993 | 9-member board; tutelage PCM | president UNVERIFIED | CSB chart |
| المؤسسة العامة لتشجيع الاستثمارات (إيدال) — IDAL | Investment Law 360 of 16-8-2001 | chairman-GM + board, tutelage PM | Chairman-GM **UNVERIFIED** (Wikipedia still lists Nabil Itani, outdated) | https://investinlebanon.gov.lb (200) |
| المجلس الاقتصادي والاجتماعي — Economic and Social Council | Law 389 of 12-1-1995 | 71 members by decree; elects its president | new composition formed early 2026; Charles Arbid re-elected president 2026-04-08 | kataeb.org 2026-04-08; An-Nahar 2026-01-30; https://www.ces.gov.lb (down) |
| المجلس الأعلى للخصخصة والشراكة — Higher Council for Privatisation and PPP | Law 228 of 31-5-2000; Law 48 of 7-9-2017 (PPP) | chaired by the PM; ministers of finance, economy, labour, justice; secretary general | Secretary General Jocelyne Jabbour (2026-09-17); launched the North free-zone project 2026-09-10 | https://www.lebanondebate.com/article/877452 ; https://www.lebanondebate.com/article/879687 ; https://www.hcp.gov.lb (down) |
| المجلس الأعلى للدفاع — Higher Defence Council | Decree-Law 102/1983 | chaired by the President; PM, ministers of defence, interior, foreign affairs, finance, economy; secretary general | SG UNVERIFIED (see 1.5) | https://ar.wikipedia.org/wiki/المجلس_الأعلى_للدفاع_(لبنان) |
| المجلس الأعلى اللبناني السوري — Lebanese-Syrian Higher Council | Treaty of 22-5-1991 | secretary general | SG Nasri Khoury (since 1994) — status 2026 UNVERIFIED; a Lebanese-Syrian business council announced 2026-05-22 | https://www.almodon.com 2026-05-22 |
| هيئة رعاية شؤون الحج والعمرة | Decree — UNVERIFIED | | | http://web.hajjandumrah.gov.lb (200) |
| مركز مشاريع ودراسات القطاع العام | | | UNVERIFIED (dormant) | pcm list |
| المؤسسة العامة لترتيب منطقة الضاحية الجنوبية الغربية لبيروت "إليسار" — Elyssar | Decree 1995 (Law 246/1995?) — UNVERIFIED | public institution | *dormant / paper-only* | pcm list |
| المجلس الأعلى للطفولة | Decree — UNVERIFIED | | | pcm list |

### 2.5 Statistics, research, higher education and administration

| Body | Creating text | Governance | Head (dated) | Site |
|---|---|---|---|---|
| إدارة الإحصاء المركزي — Central Administration of Statistics (CAS) | Decree-Law 1793 of 23-11-1979 | directorate attached to the PCM, headed by a DG | DG **UNVERIFIED** (Dr Maral Tutelian Guidanian led it 1990s–2020s) | https://www.cas.gov.lb (200); https://www.pcm.gov.lb/arabic/subpg.aspx?pageid=18198 |
| المجلس الوطني للبحوث العلمية — CNRS Lebanon | Law of 14-9-1962 | board of directors + secretary general; tutelage PCM | board appointed 2026-06-25 (https://www.lebanondebate.com/article/852335); Secretary General **UNVERIFIED** (Tamara el-Zein left the post to become minister 2025-02) | https://cnrs.edu.lb (200); Elsiyasa 2026-08-20 |
| الجامعة اللبنانية — Lebanese University | Law 75/1967 | President by decree in cabinet (5 years, renewable once) — Decree 8415 of 22-10-2021 appointed Prof. Bassam Badran | Badran's term ended 2026; exceptional 6-month extension law annulled by the Constitutional Council 2026-09-03; call for candidates open on ul.edu.lb (2026-09) | https://www.ul.edu.lb (200); https://www.lebanondebate.com/article/875306 |
| المعهد الوطني للإدارة (ENA) — National Institute of Administration | Decree 2002 — UNVERIFIED | public institution, tutelage CSB | DG UNVERIFIED | CSB chart 2026-04 |
| مؤسسة المحفوظات الوطنية — National Archives Institution | Decree 832 of 17-1-1978 | public institution, tutelage PCM, board + advisory body | UNVERIFIED | CSB chart |
| تعاونية موظفي الدولة — State Employees Cooperative | Decree — UNVERIFIED | public institution, tutelage CSB | DG Nazih Hammoud (2026-06-25) | https://www.lebanondebate.com/article/852335 |
| المركز التربوي للبحوث والإنماء (CERD) | Decree 2356/1971 | tutelage MEHE | President Hiam Ishak (2026-09-17) | see 1.13 |

## 3. State-owned and state-controlled enterprises and utilities

Legal-form vocabulary used below (from part 01 §2): *public institution* (مؤسسة عامة, Decree 4517 of 13-12-1972 general statute) with a board and a chairman-DG under a tutelage ministry; *مصلحة مستقلة* (autonomous service) — same regime, older name; *SAL wholly or majority owned by the state or by BdL* (company law applies, state appoints the board through the general assembly or by cabinet decision); *ad-hoc committee* (no legal personality).

| Entity (AR / EN) | Legal form and owner | Tutelage / ministry | Board and head as of 2026-09-18 | Status | Sources |
|---|---|---|---|---|---|
| مؤسسة كهرباء لبنان — Électricité du Liban (EDL) | public institution (Decree 16878/1964) | MoEW | Chairman Nassib Nasr (non-exec, 2026-09-17); DG Kamal Hayek; board of 6 appointed 2026-02-16 (see 1.8) | operating; chair/DG split decided 2026-09-17; concessions (Zahlé, Jbeil, Kadisha) are private companies | https://www.edl.gov.lb ; https://www.lebanondebate.com/article/879687 |
| هيئة أوجيرو — Ogero | public authority (1972), operates the state fixed network | MoT | Chairman-DG Ahmad Oueidat (2025-05-29) | operating; staff-status dispute 2026 | https://ogero.gov.lb ; https://en.wikipedia.org/wiki/Ogero |
| شركة تاتش (MIC2) / شركة ألفا (MIC1) | SALs 100% state-owned, managed since 2020 directly by the state | MoT | Alfa: chairman-GM Rafic Haddad (2025-09-16); touch: UNVERIFIED | operating; touch passed 2.7 m subscribers (Elnashra 2026-09-17) | Elnashra 1741637 |
| ليبان تيليكوم — Liban Telecom | SAL foreseen by Law 431/2002 to take over Ogero and the ministry's networks | MoT | never incorporated | *paper-only* | CSB chart notes; https://www.tra.gov.lb/Board-Members (Gemayel bio mentions the "Liban Telecom business plan") |
| مؤسسات المياه الأربع — Beirut & Mount Lebanon, North, Bekaa, South water establishments | public institutions (Law 221/2000) | MoEW | boards appointed 2026-05-22; Bekaa DG Antoine Maakaroun (2025-11-20); other DGs UNVERIFIED | operating; Central Inspection audit of Beirut & Mount Lebanon WE published 2026-08-20 (https://www.cib.gov.lb) | see 1.8 |
| المصلحة الوطنية لنهر الليطاني — Litani River Authority | public institution (Law 1954) | MoEW | President-DG Sami Alawieh + board (2026-08-07, 3 years) | operating | pcm PDF 2026-08-07; https://www.litani.gov.lb |
| طيران الشرق الأوسط — Middle East Airlines (MEA) | SAL; c. 99% owned by Banque du Liban since 1996 (not by a ministry) | none (BdL as shareholder; MPWT as aviation regulator) | Chairman-DG Mohamad El-Hout (in post 2026-07-02, Elnashra) | operating; contracted 2026-01 to rehabilitate Qleiat airport (Al-Akhbar 2026-01-29) | https://en.wikipedia.org/wiki/Middle_East_Airlines ; https://www.mea.com.lb (403) |
| شركة إنترا للاستثمار — Intra Investment Company | SAL; majority state-controlled (successor of Intra Bank, 1966) — shareholders: Lebanese state/BdL, Kuwait, Qatar (proportions UNVERIFIED) | MoF (state shareholder) | board mandate **expired** (akhbaralyawm "كهرباء لبنان والوطني للإعلام وإنترا منتهية الولاية", 2026) | holding company for Casino du Liban and real estate | https://en.wikipedia.org/wiki/Casino_du_Liban ; raw/gn_intra |
| كازينو لبنان — Casino du Liban | SAL; majority owned by Intra Investment; gaming monopoly licence renewed by decree | MoF / MoT (licence) | Chairman-GM Charles Ghostine (since 2025; replaced Roland Khoury) | operating; 2026 debate on election vs appointment of chair (An-Nahar 2026-01-15) | https://www.annahar.com/lebanon/269695 ; https://en.wikipedia.org/wiki/Casino_du_Liban |
| إدارة حصر التبغ والتنباك (الريجي) — Régie Libanaise des Tabacs et Tombacs | state monopoly administration with financial autonomy, created 1935 (Decree 1935; nationalised 1959 — UNVERIFIED) | MoF | Chairman-DG **UNVERIFIED** (Nassif Seklaoui since 2000s; board received by President Aoun 2025-26, Elnashra) | operating | https://www.regie.com.lb (200); raw/gn_regie_chair |
| مرفأ بيروت — Port of Beirut | state facility run by the Temporary Committee (1990–) — no legal personality; "شركة مرفأ بيروت ش.م.ل." approved in principle 2026-08-07 | MPWT | Committee chair Marwan al-Nafi + 6 (2025-11-06) | operating; container-terminal expansion USD 100 m launched 2026-09-10 | https://www.almodon.com/economy/2025/11/06/ ; pcm PDF 2026-08-07; https://www.portdebeyrouth.com |
| مرفأ طرابلس — Port of Tripoli | public institution (مصلحة استثمار) with 5-member board | MPWT | board appointed 2025-10-23 (chair UNVERIFIED) | operating | https://en.wikipedia.org/wiki/Port_of_Tripoli_(Lebanon) ; https://www.tripoli-port.gov.lb |
| مرفأ صيدا; مرفأ صور — Sidon and Tyre ports | public institutions (مصلحة استثمار) | MPWT | heads vacant (2026-09) | operating | CSB charts 2023 |
| المديرية العامة للطيران المدني + مطار رفيق الحريري الدولي — DGCA / Beirut airport | DGCA is a directorate of MPWT (Law 481/2002 authority never constituted); airport operations to be run by "مؤسسة مطار بيروت الدولي ش.م.ل." | MPWT | DGCA: Amin Jaber; airport SAL: Chairman-GM Mohammad Chatila (2026-07-09) + board (2026-08-07) | transitional | pcm PDFs; https://www.beirutairport.gov.lb |
| مطار الرئيس رينيه معوض — Qleiat airport | state airport, inaugurated 2026-06-06; first international licence to IBEX Air Charter 2026-07-09 | MPWT | — | operating (limited) | pcm PDF 2026-07-09; https://en.wikipedia.org/wiki/2026_in_Lebanon |
| مصلحة سكك الحديد والنقل المشترك — RPTA | public institution (مصلحة) | MPWT | chair/DG UNVERIFIED; chair/DG post listed as to be filled (TMO is the vacant one; RPTA status UNVERIFIED) | operating (bus services; rail dormant) | CSB chart 2023 |
| هيئة إدارة السير والآليات والمركبات — Traffic and Vehicles Management Authority (TMO) | public institution (Law 243/2012) | MoIM | chair/DG vacant; board granted staff allowances 2026-07-09 | operating | pcm PDF 2026-07-09; https://tmo.gov.lb |
| مستشفى رفيق الحريري الجامعي — RHUH | public institution (hospital) | MoPH | DG Mohammad Salim Zaatari (2025-08-13) | operating; new PET-scan unit 2026-07-31 | https://www.lebanondebate.com/article/732036 |
| المستشفيات الحكومية (~29) — governmental hospitals | public institutions each with a board | MoPH | ~29 chair/board posts vacant; batch appointment planned (2026-09) | operating | https://kataeb.org/articles/sl-241234 |
| بورصة بيروت — Beirut Stock Exchange (BSE) | public institution run by a committee (chair, vice-chair, 8 members by decree on MoF proposal); Law 2017 on conversion to an SAL (UNVERIFIED number) not implemented | MoF; regulated by CMA | Chairman **UNVERIFIED** (Fadi Khalaf per Wikipedia, possibly outdated) | operating (15 listings) | https://en.wikipedia.org/wiki/Beirut_Stock_Exchange |
| تلفزيون لبنان ش.م.ل. — Télé Liban | SAL, 100% state | Ministry of Information | Chairwoman-DG Dr Elissar Naddaf Geagea (appointed 2025-07-11) + 5 board members | operating; pay freeze debate 2026-04-30 | https://en.wikipedia.org/wiki/T%C3%A9l%C3%A9_Liban ; https://www.lebanondebate.com/article/721341 |
| الوكالة الوطنية للإعلام — National News Agency | directorate of the Ministry of Information (no legal personality) | Ministry of Information | director UNVERIFIED | operating | https://www.nna-leb.gov.lb |
| إذاعة لبنان — Radio Lebanon | directorate of the Ministry of Information | Ministry of Information | UNVERIFIED | operating | |
| هيئة إدارة قطاع البترول — LPA | public institution (Law 132/2010) | MoEW | board 2026-06-25 | operating; Block 8 exploration agreement with TotalEnergies approved 2025-10-23 | https://www.lpa.gov.lb ; An-Nahar 2025-10-23 |
| الصندوق السيادي — Sovereign Wealth Fund | draft only | — | — | *paper-only* | section 2.1 |
| ليبان بوست — LibanPost | private SAL (concession) — not state-owned | MoT | — | operating | https://ar.wikipedia.org/wiki/ليبان_بوست |
| معرض رشيد كرامي الدولي — Rachid Karami International Fair (Tripoli) | public institution; board of chair + 6 by decree, 3 years; audited by Court of Audit | MoET | chair UNVERIFIED | operating (UNESCO World Heritage 2023) | https://ar.wikipedia.org/wiki/معرض_رشيد_كرامي_الدولي |
| هيئة المنطقة الاقتصادية الخاصة في طرابلس — Tripoli Special Economic Zone Authority | authority created by Law 18 of 5-9-2008 | PCM | board UNVERIFIED; the HCP launched a "North free economic zone" investment project 2026-09-10 | operating (early stage) | https://www.lebanondebate.com/article/877452 |
| المؤسسة العامة للإسكان — Public Housing Institution | public institution (Law 539/1996) | MoSA (CSB) | UNVERIFIED | operating (loans suspended since 2019) | CSB chart |
| مصرف الإسكان — Housing Bank | mixed SAL (state 20% via BdL/MoF — UNVERIFIED) | — | 50th anniversary 2026-06-30 | operating | Al-Liwaa 2026-06-30 |
| المؤسسة العامة للمنشآت الرياضية والكشفية والشبابية | public institution (Decree 16681/2006) | MYS | DG Naji Hammoud (2026-04-02) | operating | see 1.20 |
| مديرية اليانصيب الوطني — National Lottery | directorate of MoF | MoF | DG UNVERIFIED | operating; sports betting reassigned 2026 | raw/gn_lottery |
| إليسار; المؤسسة العامة للأسواق الاستهلاكية; المؤسسة العامة للزيت والزيتون; المؤسسة العامة للزراعات البديلة; هيئة مراكز التجمع الصناعي; ليبان تيليكوم; هيئة الطيران المدني (Law 481/2002) | *paper-only or dormant* public institutions/authorities | various | — | never or no longer constituted | CSB charts; pcm list |

## 4. Counts, to sanity-check the "500+ nodes" v1 scope

Counting rule: one node per legal or organisational unit that has its own head; ministers and DGs are *positions* on those nodes (CivLab "dept_head"), not separate organisation nodes. Counts are of what this inventory actually enumerates (section-by-section), then a rough estimate of what a complete enumeration would add.

| Category | Enumerated here | Notes / to complete |
|---|---|---|
| Council of Ministers seats (elected/appointed positions) | 24 (section 0) | + PM and Deputy PM roles are seats |
| Ministries (department nodes) | 22 + PCM + OMSAR + MoS IT&AI (no ministry) = 25 | Ministry of Technology & AI would add 1 if its law is promulgated |
| Directorates general and equivalent grade-1 posts inside ministries | ≈ 45 listed (sections 1.0–1.24) | official total of grade-1 posts is 149 (Al-Liwaa via MTV 2025-12-24: "62 وظيفة شاغرة ... من أصل 149 في الفئة الأولى"); governors (9) and mission heads not listed |
| Security services (directorates) | 5 (LAF, ISF, General Security, State Security, Civil Defence) + Customs | part 04 |
| Public institutions / autonomous services under ministerial tutelage | ≈ 55 named (EDL, Ogero, 4 water, Litani, LPA, LARI, Green Plan, CERD, LU, NSSF, NEO, PHI, RHUH, TMO, RPTA, 3 port authorities, sports facilities, National Library, Conservatoire, Museums, CISH, State Employees Coop, ENA, National Archives, CFD, Council of the South, IDAL, Consumer Markets, LIBNOR, IRI, Mukhtars' fund, Judges' fund, Institut des Finances, Elyssar, RKIF, TSEZ, ~29 governmental hospitals counted as one class) | with each public hospital as its own node: +28; add CSB's full list of "المؤسسات العامة" (~60 institutions on https://www.csb.gov.lb/ar/الهيكليات/) |
| Regulators and independent authorities | 14 (BdL, BCC, CMA, SIC, Higher Banking Commission, ICC, TRA, ERA, LPA, Competition, PPA, Solid Waste, Cannabis, Food Safety) + NCAV | |
| Oversight, constitutional and rights bodies | 11 (CSB, Central Inspection, Higher Disciplinary, Court of Audit, Constitutional Council, NACC, SCE, NHRC, NCLW, Missing Commission, Higher Judicial Council — the latter in part 03) | |
| Councils, funds and commissions attached to PCM/Presidency | 14 (CDR, Council of the South, HRC, CFD, ESC, HCP, HDC, Lebanese-Syrian Council, Hajj authority, Public Sector Projects Centre, Higher Council for Childhood, CNRS, CAS, National Commission for the Missing) | plus religious councils/courts listed on pcm 4211 (8) → part 03 |
| State-owned or state-controlled companies (SAL) | 9 (MEA, Intra, Casino, touch, Alfa, Télé Liban, Beirut Airport SAL, Housing Bank (mixed), Port of Beirut SAL (planned)) + Régie + BSE | |
| Paper-only / dormant bodies flagged | 10 | keep as nodes with status = paper_only |
| **Executive subtotal (organisation nodes)** | **≈ 150–160 distinct bodies named**, ≈ 200 with hospitals and the full CSB list | |
| Positions (dept_head nodes) attached | ≈ 24 ministers + ≈ 45 DGs + ≈ 60 chairs/DGs of institutions + 9 governors ≈ 140 | |

Rough total for the executive sector alone: ≈ 200 organisations + ≈ 140 head positions ≈ 340 nodes. With Parliament (128 seats + blocs + committees ≈ 160), the judiciary (≈ 40) and the constitutional core (≈ 60, overlapping), the "500+ nodes" v1 scope is realistic, and probably closer to 600–650 if every governmental hospital, water establishment board and regulator board member is modelled as a person node.

## 5. Implications for the civicleb model

1. **Subtypes.** The decided list (ministry, public_institution, security_service, regulator, court, confessional_court, central_bank, seat) covers most of the inventory, but three families do not fit cleanly:
   - *Oversight/control bodies attached to the PCM* (Civil Service Board, Central Inspection, Higher Disciplinary Authority, Court of Audit, NACC, SCE, NHRC, Constitutional Council). They are neither departments nor regulators nor courts (the Court of Audit is both a court and an audit body). Suggest a subtype `oversight` (or reuse CivLab `commission` with subtype `oversight`).
   - *Councils/funds attached to the PCM* (CDR, Council of the South, HRC, CFD, HCP, ESC, HDC). CDR/Council of the South/CFD/IDAL are public institutions in law and can take `public_institution`; ESC is `advisory`; HCP and HDC are ministerial committees chaired by the PM/President — suggest `council` (an executive committee of ministers) as a subtype of `commission`.
   - *State-owned companies* (MEA, Intra, Casino, touch/Alfa, Télé Liban, Beirut Airport SAL, BSE-to-be): company law, not public law. Suggest subtype `state_company` distinct from `public_institution`, with an `ownership` attribute (owner node + share).
   Also needed: `directorate_general` as a node type (or a flag on `department`) because DGs are the operational units that persist across governments and are where most "changes since January 2025" happen; and a `paper_only` / `dormant` status enum on every organisation node (ERA 2002–2025, Civil Aviation Authority, Liban Telecom, NCAV expired, Constitutional Council expired).
2. **Department vs public_institution vs regulator boundary.** It holds on the legal test in part 01 §2 (legal personality + tutelage = public institution; created by law with independence guarantees + licensing/sanction powers = regulator; no legal personality = department/directorate). Edge cases to encode explicitly: Ogero (authority created to run a nationalised company; behaves like an SOE), Régie (administration with financial autonomy), the Port of Beirut committee (no legal personality at all), DGCA (department standing in for a paper-only authority), NNA/Radio Lebanon (directorates of a ministry that the public treats as media companies), TMO/RPTA/port authorities (public institutions styled مصلحة/هيئة). The customs administration is a department with a collegial head (Higher Council) — model the Council as a `commission` node and the DG as a `dept_head`.
3. **Tutelage as an edge type.** The Lebanese relationship is وصاية (tutelle), exercised by a minister over a public institution: prior approval of budgets, boards and major decisions, and the power to propose board appointments to cabinet (Decree 4517/1972; CSB charts print "سلطة الوصاية: …" for each institution). It is distinct from hierarchical authority (سلطة سلمية/رئاسية) inside a ministry (minister → DG → directorates) and from ownership of a company. Recommended edge types: `reports_to` (hierarchy inside a legal person: DG → minister; directorate → DG), `tutelage_of` (ministry → public institution; PCM → CDR; CSB → ENA), `owns` (state/BdL → SAL, with share), `appoints` (Council of Ministers → board/DG, with the decree as source), `regulates` (regulator → sector entities), `member_of` (person → board/council). Tutelage should carry the legal source attribute (law/decree article) and be allowed to point at the PCM or the CSB, not only at ministries.
4. **Appointing authority as data.** Almost every officeholder claim in this inventory is sourced to a Council of Ministers session; the sweep in decisions.md (pcm.gov.lb decrees + NNA) should target the session decision PDFs ("مقررات جلسة ...pdf" on pcm.gov.lb), which are machine-readable and list appointments with terms — they are the primary source for tenure start dates. Presidential decrees (security chiefs, Military Council) and ministerial decisions (acting DGs "بالتكليف", Military Court) are second and third sources.
5. **Acting vs titular.** A large share of DG posts are held بالتكليف/بالإنابة (MoF chart: 15 of 20 directors acting; Tourism DG acting since 2023; Youth & Sports since 2024; Education DG assigned since 2022). Tenure records need an `acting` boolean and a `basis` (decree / ministerial assignment).
6. **Confession of positions.** The customs and grade-1 reporting (Lebanon Debate 781308; kataeb.org sl-241234) shows the working allocation of posts by sect is real but customary. Per decisions.md Q8, record it on the position with basis = custom and the press source, never on the person.
7. **Open items for the next pass (highest value first):** the four BdL vice-governors; heads of Regie, BSE, IDAL, CAS, CFD, Higher Council for Childhood, Public Housing Institution, LARI, Green Plan, NEO, LIBNOR, IRI, National Library, Conservatoire; DGs of Interior (Personal Status, Local Administrations, Political Affairs), MFA SG, Telecom Construction and Post DGs, Public Works Roads and Urban Planning DGs, Industry, Environment, Culture, Cooperatives, Vocational Education; the LPA, CNRS, Solid Waste Authority and SCE member lists; the Tripoli port board; and confirmation whether the Ministry of Technology & AI law was promulgated. The legallaw.ul.edu.lb database (unreachable on 2026-09-18) is the place to confirm the creating-text numbers marked UNVERIFIED.

Status: sections 0–5 complete (2026-09-18). Items marked UNVERIFIED remain to be confirmed against primary sources.

---


# Part 3 — Judiciary, oversight and control, security services

Research note for civicleb. Date of research: 2026-09-18. Every officeholder claim is dated; items that could not be confirmed against a primary or reputable secondary source are marked **UNVERIFIED**. Taxonomy and edge types follow `docs/decisions.md` (Q29).

## 1. Ordinary judiciary

Sources are marked P (primary: official site or legal text), S (reputable secondary) and GN (headline found through the Google News RSS search `https://news.google.com/rss/search?q=<query>&hl=ar&gl=LB&ceid=LB:ar`; the outlet, date and headline are given so the article can be located; the article body was not read unless stated). Constitutional articles and the creating texts of the Higher Judicial Council, the Judicial Council, the Supreme Council for the Trial of Presidents and Ministers, the State Council, the Court of Audit and the Constitutional Council are in Part 01 §1.1 and §3.1 and are not repeated here.

### 1.1 Legal frame in one paragraph

The ordinary ("judicial", عدلي) courts are organised by Legislative Decree 150 of 16/9/1983 (قانون القضاء العدلي) as amended (Part 01 §1.1). Parliament replaced it with the **Judicial Organisation Law 36 of 5/1/2026** (published OG 3, 15/1/2026), but the **Constitutional Council annulled that law in its entirety by Decision 1/2026 of 25/2/2026** on two petitions (reviews 1/2026 and 3/2026, one from 14 opposition deputies asking partial annulment, one from 10 Free Patriotic Movement deputies asking full annulment) — https://www.cc.gov.lb/ar/القرارات/قرارات-دستورية-القوانين/?page=2 (P, listing); Annahar 25/2/2026 "المجلس الدستوري أبطل بالأكثرية قانون تنظيم القضاء العدلي برمته" (GN); Legal Agenda 26/2/2026 "المجلس الدستوريّ يبطل قانون القضاء العدليّ: هكذا تمّ القضاء على النظام البرلمانيّ" (GN); Al Akhbar 26/2/2026 "إبطال قانون «استقلالية القضاء»: «إنجاز تاريخي»" (GN). **Consequence for the graph: Legislative Decree 150/1983 remains the law in force as of 2026-09-18; the HJC composition to model is the 3 + 2 + 5 formula of Part 01, not the 2025–26 reform formula.** The decision's reasoning (PDF not read) is **UNVERIFIED** as to whether any article survived; the listing and all three headlines say the whole law was struck.

Chronology of the reform attempt (S: HRW and Legal Agenda, GN headlines): law voted 31/7/2025; returned by the President by decree signed 5/9/2025 (MTV 5/9/2025 "عون وقّع المرسوم القاضي بإعادة قانون تنظيم القضاء العدلي الى مجلس النواب", GN); re-adopted 18/12/2025 (Legal Agenda 18/12/2025 "إقرار ثان لقانون القضاء العدلي", GN); promulgated as Law 36 of 5/1/2026; annulled 25/2/2026. No replacement bill had been passed as of 2026-09-18 (no GN hit for a new law; treat as **UNVERIFIED / likely none**).

### 1.2 Bodies

| Body | Legal basis | Composition / how filled | Head as of 2026-09-18 | Src |
|---|---|---|---|---|
| Higher Judicial Council (مجلس القضاء الأعلى) | Leg. Decree 150/1983 Art. 2 as amended (Part 01 §1.1) | 10 members: 3 ex officio (First President of Cassation = president; Public Prosecutor at Cassation = vice-president; President of Judicial Inspection), 2 elected by Cassation judges from among Cassation chamber presidents (3 yrs), 5 appointed by decree on the Minister of Justice's proposal (3 yrs, non-renewable). Site: https://www.csj.gov.lb/ (unreachable this session). Ministry summary: https://www.justice.gov.lb/index.php/courts/2 (P, menu entry "مجلس القضاء الاعلى") | **Judge Souheil Abboud (سهيل عبود)**, First President of Cassation and HJC president, in office since 2019 and still acting as such: he chaired HJC sessions on the partial judicial permutations on 15–17 September 2026 (Al Akhbar 15/9/2026 "عبّود لا يأبه للحاج: إصدار التشكيلات غداً"; Lebanon Debate 17/9/2026 "وزير العدل عادل نصار عقد اجتماعًا مع القضاة سهيل عبود وأحمد رامي الحاج وأسامة منيمنة قبيل جلسة لمجلس القضاء الأعلى"; GN). Start date of his tenure (2019 decree number) **UNVERIFIED** this session. | GN |
| Court of Cassation (محكمة التمييز) | Leg. Decree 150/1983 Arts. 20 ff. (article numbers **UNVERIFIED**) | **10 chambers**, all in Beirut, listed as "محكمة التمييز – الغرفة الأولى … العاشرة – بيروت" on the Ministry's judicial map — https://www.justice.gov.lb/index.php/service-details/3/2 (P, read 2026-09-18). Plenary assembly (الهيئة العامة) elects the Public Prosecutor at Cassation for the Supreme Council (Const. Art. 60) and hears personal-status conflicts of jurisdiction. Chamber presidents are appointed by decree on the HJC's draft: a decree naming Cassation chamber presidents awaited the President's and PM's signatures on 30/4/2025 (Lebanon Debate 30/4/2025 "مرسوم تعيين رؤساء التمييز ينتظر توقيعي عون وسلام", GN). | First President: Souheil Abboud (above). | P/GN |
| Public Prosecution at Cassation (النيابة العامة التمييزية) | Code of Criminal Procedure (Law 328/2001) Arts. 13 ff.; Leg. Decree 150/1983 | One office in Beirut (judicial map, P). Head appointed by decree taken in the Council of Ministers on the Minister of Justice's proposal after HJC opinion (Leg. Decree 150/1983 Art. 5 ff.; article **UNVERIFIED**). Heads the hierarchy of appellate public prosecutors and the Financial Public Prosecutor; sits ex officio as HJC vice-president. | **Judge Ahmad Rami al-Hajj (أحمد رامي الحاج)**, appointed by the Council of Ministers on **30/4/2026** (Al-Markazia 30/4/2026 "مجلس الوزراء يُعين الحاج مدعيا عاما ومنيمنة رئيسا لهيئة التفتيش"; Lebanon 24 30/4/2026 "أحمد الحاج مدعياً عاماً تمييزياً"; L'Orient Today 30/4/2026 "Cabinet appoints Ahmad Hajj as public prosecutor at Court of Cassation"; GN). Predecessor Judge Jamal Hajjar (جمال الحجار) retired in late April 2026; Judge Francis (فرنسيس; first name **UNVERIFIED**) served as acting prosecutor from 24/4/2026 (Elnashra 24/4/2026 "القاضي فرنسيس نائباً عاماً تمييزياً بالانابة خلفاً للقاضي جمال الحجار", GN). Decree number **UNVERIFIED**. | GN |
| Financial Public Prosecution (النيابة العامة المالية) | Law 328/2001 Art. 13 bis (article **UNVERIFIED**) | One office in Beirut (judicial map, P). | Judge Maher Shaito (ماهر شعيتو) is referred to as the financial prosecutor in 2026 coverage (Elnashra 24/5/2026 "القاضي شعيتو يستدعي مدير عام كهرباء لبنان", GN) — **treat as UNVERIFIED** until a decree is cited. | GN/U |
| Appellate public prosecutions (النيابات العامة الاستئنافية) | Law 328/2001 | **6**: Beirut, Mount Lebanon (Baabda), North (Tripoli), South (Sidon), Bekaa (Zahle), Nabatieh — judicial map (P). Note: the map has no separate Baalbek-Hermel or Akkar prosecution; those governorates (created 2003, activated 2014–17) are served from Zahle and Tripoli. | — | P |
| Investigation departments (دوائر التحقيق) | Law 328/2001 | **7** investigating-judge departments: Beirut, Baabda, Tripoli, Sidon, Zahle, Baalbek, Nabatieh — judicial map (P). | — | P |
| Courts of Appeal (محاكم الاستئناف) | Leg. Decree 150/1983 | **6 courts, 46 chambers**: Beirut 13; Mount Lebanon 13 (chambers 1–8 in Baabda, 9–13 in Jdeidet el-Metn); North 7 (Tripoli); South 5 (Sidon); Bekaa 5 (1–3 Zahle, 4–5 Baalbek); Nabatieh 3 — judicial map (P). Each court has a First President (رئيس أول) — names **UNVERIFIED**. | — | P |
| First-instance courts (محاكم الدرجة الأولى) | Leg. Decree 150/1983 | Collegiate chambers plus single-judge seats (قاضي منفرد) by district: Beirut 7 chambers; Mount Lebanon 9 chambers (4 Baabda, 5 Jdeidet el-Metn) + 9 single-judge seats (Damour, Baakline, Jbeil, Jdeideh, Jounieh, Deir el-Qamar, Chhim, Aley, Qartaba); North 2 chambers (Tripoli) + 10 seats (Batroun, Dinniyeh, Qobayat, Minieh, Amioun, Bcharre, Halba, Douma, Zgharta-Ehden, Tripoli); South 1 chamber (Sidon) + 4 seats (Jezzine, Joya, Tyre, Sidon); Bekaa 2 chambers (Zahle) + 7 seats (Hermel, Baalbek, Jib Jannine, Deir el-Ahmar, Rachaya, Zahle, Ras Baalbek); Nabatieh 1 chamber + 5 seats (Nabatieh, Bint Jbeil, Tebnine, Hasbaya, Marjeyoun) — judicial map (P; the North list was cut by page noise and may be incomplete). **Totals from the map: 22 chambers and 35 single-judge seats.** | — | P |
| Labour arbitration councils (مجالس العمل التحكيمية) | Labour Code (Law of 23/9/1946) Art. 77 ff. | **11** councils: Beirut 5; Mount Lebanon 2 (Baabda); Tripoli 1; Sidon 1; Zahle 1; Nabatieh 1 — judicial map (P). Mixed composition (judge + employer and worker representatives). | — | P |
| Judicial Inspection (هيئة التفتيش القضائي) | Leg. Decree 150/1983 Arts. 98 ff. (article **UNVERIFIED**) | President (ex officio HJC member) and inspectors general; president appointed by decree taken in the Council of Ministers on the Minister of Justice's proposal. Ministry page: https://www.justice.gov.lb/index.php/courts/2 (P, menu). | **Judge Osama Mneimneh (أسامة منيمنة)**, appointed by the Council of Ministers on **30/4/2026** in the same session as the prosecutor (Al-Markazia 30/4/2026, GN); sworn in before President Aoun on **9/7/2026** "عضوًا في مجلس القضاء الأعلى ومكتب مجلس شورى الدولة" (Elnashra 9/7/2026 "رئيس هيئة التفتيش القضائي القاضي أسامة منيمنة أقسم اليمين القانونية أمام الرئيس جوزاف عون"; Elsiyasa 9/7/2026; GN). Predecessor **UNVERIFIED**. | GN |
| Institute of Judicial Studies (معهد الدروس القضائية) | Decree 7855 of 16/10/1961 (creation, under the 1961 judicial organisation); Leg. Decree 150/1983 Part III ch. 2; Law 133 of 14/4/1992 (financial section) | A unit of the Ministry of Justice's Directorate General, no legal personality or own budget. Board: HJC president (chair), Ministry DG (vice-chair), Institute president, Institute director, two judges named by the Minister of Justice with HJC approval (3 yrs, once renewable). For the administrative section the State Council Bureau and its president replace the HJC; for the financial section the Court of Audit's Bureau and president. Entrance competition organised by the HJC / State Council Bureau at the Minister's request; for financial judges the PM replaces the Minister — https://www.justice.gov.lb/index.php/department-details/3/2 (P). | President of the Institute: **UNVERIFIED**. | P |
| Ministry of Justice – Directorate General (المديرية العامة لوزارة العدل) | Leg. Decree 151 of 16/9/1983 as amended by Leg. Decree 23/1985 | DG (a judge), Directorate of Judges' and Employees' Affairs, Legislation and Consultation Commission (هيئة التشريع والاستشارات), Cases Commission (هيئة القضايا, represents the State in litigation), prisons directorate, forensic medicine, commercial register — https://www.justice.gov.lb/index.php/department-details/19/2 (P). | DG: **UNVERIFIED** this session (page https://www.justice.gov.lb/index.php/minister/2?tab=2 returned only navigation). | P/U |

### 1.3 Court-unit count (ordinary judiciary, from the Ministry's judicial map, read 2026-09-18)

| Unit type | Count |
|---|---|
| Court of Cassation chambers | 10 |
| Public prosecution at Cassation / Financial prosecution | 1 + 1 |
| Courts of Appeal (courts / chambers) | 6 / 46 |
| Appellate public prosecutions | 6 |
| Investigation departments | 7 |
| First-instance chambers / single-judge seats | 22 / 35 |
| Labour arbitration councils | 11 |
| Permanent Military Court + military investigation department (see §2) | 1 + 1 |
| **Total units on the map** | **147** (of which 6 governorate-level appellate jurisdictions) |

Recommended node granularity: one `court` node per jurisdiction level and governorate (Cassation; 6 courts of appeal; 6 first-instance jurisdictions; 6 appellate prosecutions), with chamber counts as attributes, not nodes. That is 19 court nodes plus the HJC, Judicial Inspection, Cassation prosecution, financial prosecution and the Institute — 24 nodes for §1.

### 1.4 The 2025 judicial permutations (التشكيلات القضائية) and what followed

- The HJC adopted a draft of general permutations on 30/7/2025 (Al-Markazia 30/7/2025 "بالأسماء... 'القضاء الأعلى' يُقرّ مشروع التشكيلات القضائية.. نصار: لا محاصصة", GN); the PM signed on 1/8/2025 (Elnashra 1/8/2025, GN); **President Aoun signed Decree 823 of 5/8/2025** (Lebanon Debate 5/8/2025 quoting NNA: "الرئيس جوزاف عون وقّع مرسوم التشكيلات والمناقلات القضائية رقم 823 بتاريخ 5 آب 2025", GN; L'Orient Today 5/8/2025 "Aoun signs decree on judicial appointments", GN). These were the first general permutations since 2017 (HRW World Report 2026, https://www.hrw.org/world-report/2026/country-chapters/lebanon, S). Critical reading: Al Akhbar 18/8/2025 "أوهام إصلاح القضاء: تشكيلات مكررة وتكريس المحاصصة" (GN); Legal Agenda 20/8/2025 "كرونولوجيا إعادة الحياة للمرفق القضائي" (GN).
- A **decree appointing the members of the Judicial Council** was signed by the Minister of Justice on 12/9/2025 (Elnashra, Kataeb, Annahar 12/9/2025, GN) — see §2.
- Partial permutations (تشكيلات جزئية) in 2026: the HJC and the new Public Prosecutor at Cassation disagreed publicly over criteria and powers from July 2026 (Al Akhbar 13/7/2026 "«حرب باردة» بين مجلس القضاء والمدّعي العام"; Al Akhbar 10/8/2026 "عبّود يرفض التشكيلات الجزئية: هل تُطيح بعبدا برئيس «مجلس القضاء الأعلى»؟"; VDL 14/9/2026; GN). A partial permutation was expected to be issued by the HJC on 16–17/9/2026 and to await the President's signature (Al Modon 16/9/2026 "التشكيلات القضائية إلى التوقيع: فيتو بعبدا ينتظر عبود؟"; Lebanon Debate 17/9/2026 "خلاف قضائي ينتهي بتسوية", GN). **Whether the decree was issued by 2026-09-18: UNVERIFIED.**
- Entrance competition to the Institute of Judicial Studies announced for the first time in eight years (Alkalima 5/9/2026 "8 سنوات من الانتظار... مباراة تعيد رسم المشهد القضائي", GN).
- Judicial appointments therefore remain a multi-stage edge in the data model: `HJC —advises→ MinisterOfJustice` (draft) → `MinisterOfJustice —confirms→` → decree signed by President, PM and Minister. Under Leg. Decree 150/1983 Art. 5 the HJC can override the Minister's refusal by 7 votes (Part 01 §1.1).

## 2. Administrative, financial and exceptional jurisdictions

Creating texts and appointment rules for the State Council, Court of Audit, Judicial Council, Supreme Council and Constitutional Council: Part 01 §1.1 and §3.1. This section adds structure, counts and current holders.

| Body | Nature / legal basis | Structure | Head and members as of 2026-09-18 | Src |
|---|---|---|---|---|
| State Council (مجلس شورى الدولة) | Supreme administrative court and adviser to the government; Leg. Decree 10434 of 14/6/1975 as amended (Law 227/2000). Attached administratively to the Ministry of Justice — https://www.justice.gov.lb/index.php/court-details/20/2 (P, Part 01). | President; Government Commissioner (vice-president of the Bureau); chamber presidents and counsellors; **6 chambers** (1 advisory/administrative, 5 judicial); **Bureau of the Council** (مكتب المجلس) = president, Government Commissioner, chamber presidents, presidents of the highest administrative courts — it plays for administrative judges the role the HJC plays for ordinary judges (Institute of Judicial Studies page, P). First-instance administrative courts re-created by Law 227/2000: **not yet operating** as of the 2020s (S; **UNVERIFIED** for 2026). | **Judge Youssef Gemayel (يوسف الجميّل)**, president; he briefed President Aoun on the Council's work on 7/8/2026 (Lebanon Debate 7/8/2026 quoting the Presidency: "رئيس مجلس شورى الدولة القاضي يوسف الجميّل أطلع الرئيس عون على سير العمل في المجلس"; MTV 7/8/2026; GN). Appointment decree and date **UNVERIFIED**. Former president Judge Shukri Sader (شكري صادر) is described as "السابق" (MTV 9/8/2026, GN). | GN |
| Court of Audit (ديوان المحاسبة) | Administrative court exercising financial jurisdiction and a-priori/a-posteriori audit; Leg. Decree 82 of 16/9/1983; attached administratively to the Presidency of the Council of Ministers — https://www.coa.gov.lb/ar/عن-الديوان (P, Part 01). Site: https://www.courdescomptes.gov.lb/ (redirects to coa.gov.lb; not re-fetched). | President; Public Prosecutor at the Court (النيابة العامة لدى الديوان); chamber presidents and counsellors; **Bureau of the Court** (مكتب الديوان) which for financial judges replaces the HJC (Institute page, P). Number of chambers **UNVERIFIED**. Ministry of Justice explanatory pages: https://www.justice.gov.lb/index.php/courts/2 (P, section "ديوان المحاسبة": nature, subject bodies, organisation, competences, remedies, relations with public authorities). | **Judge Mohammad Badran (محمد بدران)**, president (coa.gov.lb home page, read 2026-09-18, P; Part 01). Still referred to as president in July–August 2026 (MTV 24/7/2026 "الحجّار بحث مع رئيس ديوان المحاسبة"; Al Akhbar 1/8/2026 "رئيس ديوان المحاسبة يراقبُ قُضاتَه"; GN, no name in headline). Appointment date **UNVERIFIED**. Public Prosecutor at the Court: **UNVERIFIED**. | P/GN |
| Judicial Council (المجلس العدلي) | Exceptional criminal court for crimes against state security referred by decree in the Council of Ministers; Law 328/2001 Arts. 355–366 (Part 01). | First President of Cassation (president) + 4 Cassation judges named by decree on the Minister of Justice's proposal after HJC approval; judgments final subject to objection/retrial (Art. 366). | Chair ex officio: Souheil Abboud. **Members appointed by decree signed by the Minister of Justice on 12/9/2025** (Elnashra 12/9/2025 "وزير العدل وقّع مرسوم تعيين أعضاء المجلس العدلي"; Annahar 12/9/2025 "صدور مرسوم تعيين أعضاء المجلس العدلي (صورة)"; Kataeb 12/9/2025; GN). Names and decree number **UNVERIFIED** (article bodies not read). Before that the Council had been paralysed for lack of members (Legal Agenda 13/8/2025 "عدالة معطّلة في انتظار تعيين أعضاء المجلس العدلي", GN); it resumed hearings in the Tleil explosion case on 15/11/2025 (Legal Agenda, GN). The Beirut port explosion investigation is before the judicial investigator (المحقق العدلي) Judge Tarek Bitar under the Judicial Council procedure (S: widely reported; not re-verified this session). | GN |
| Permanent Military Court (المحكمة العسكرية الدائمة) and Military Cassation Court (محكمة التمييز العسكرية) | Code of Military Justice, Law of 13/4/1968 (قانون القضاء العسكري; number/date **UNVERIFIED** this session — Ministry of Justice pages list "قانون القضاء العسكري", "مفوض الحكومة ومعاونوه", "قضاة التحقيق", "صلاحية المحاكم العسكرية" at https://www.justice.gov.lb/index.php/courts/2, P). Attached to the Ministry of National Defence. | Judicial map (P): 1 Permanent Military Court (Beirut), 1 military investigation department (دائرة التحقيق العسكرية, Beirut). The Permanent Military Court is presided by an officer with a civilian judge and officers as members; the Military Cassation Court is presided by a civilian judge seconded from the ordinary judiciary; the Government Commissioner (مفوض الحكومة) is a civilian judge who acts as prosecutor. Single-judge military courts (القاضي المنفرد العسكري) exist in the governorates (**count UNVERIFIED**). | Permanent Military Court president: **Brig. Gen. Wassim Fayyad (وسيم فياض)**, named by decision of Defence Minister Maurice Slim on 15/1/2025 with the other presidents and members (Elnashra 15/1/2025 "وزير الدفاع أصدر قرارا بتعيين هيئة جديدة للمحكمة العسكرية برئاسة العميد وسيم فياض"; Janoubia 15/1/2025; Annahar 15/1/2025; GN). His predecessor Brig. Gen. Khalil Jaber was named 1/11/2022 (Elnashra, GN). **Whether Fayyad is still president in September 2026: UNVERIFIED** (a March 2026 headline asks whether he would be removed: Sawt Beirut International 9/3/2026, GN). Government Commissioner: **Judge Claude Ghanem (كلود غانم)** (Al Jadeed 5/5/2026; LBCI via Akhbar al-Yawm 8/7/2026; Elnashra 28/7/2026; GN); appointment instrument **UNVERIFIED** (presumably Decree 823/2025). Military Cassation Court president: changed in Nov 2025 and again in March 2026 (Saida Online 21/11/2025 "من هو الرئيس الجديد لمحكمة التمييز العسكرية؟"; Lebanon Debate 31/3/2026 "رئيس جديد لمحكمة التمييز العسكرية"; GN) — **name UNVERIFIED**. | GN |
| Supreme Council for the Trial of Presidents and Ministers (المجلس الأعلى لمحاكمة الرؤساء والوزراء) | Const. Arts. 60, 70, 71, 80; Law 13/1990 (Part 01). | 7 deputies elected by Parliament + 8 most senior judges; presided by the highest-ranking judge. | Deputy members elected 26/7/2022 (Part 01, P). Names **UNVERIFIED**. Has never tried anyone (S). Model as `never_convened` rather than vacant. | P/S |
| Constitutional Council (المجلس الدستوري) | Const. Art. 19; Law 250/1993 as amended (Part 01). Site: https://www.cc.gov.lb/ | 10 members: 5 elected by Parliament, 5 appointed by decree in the Council of Ministers (2/3), 6-year non-renewable terms; president and vice-president elected by the members (Law 250 Art. 5 — article **UNVERIFIED**). | Members listed on https://www.cc.gov.lb/ar/members (P, read 2026-09-18): **Judge Tannous Mechleb (طنوس مشلب), president; Judge Omar Hassan Hamza (عمر حسن حمزة), vice-president; Judge Awni Ramadan (عوني رمضان); Judge Albert Aziz Serhan (البير عزيز سرحان); Judge Michel Tarazi (ميشال طرزي); Judge Mireille Émile Najm (ميراي أميل نجم); Judge Elias Mechreqani (الياس مشرقاني); Judge Riad Abou Ghida (رياض أبو غيدا); Judge Ahmad Akram Baasiri (أحمد أكرم بعاصيري); Judge Fawzat Farhat (فوزت فرحات)** — 10 of 10 seats, **no vacancy shown**. This is the bench installed in 2019 (S; installation date and decree **UNVERIFIED**). Its six-year term expired in 2025 and no renewal was found: Kataeb 1/9/2025 "ولاية الدستوري انتهت...هل يدعو بري الى جلسة لانتخاب الأعضاء؟"; Al Modon 6/9/2025 "الخلافات السياسية تُعيق تشكيل المجلس الدستوري"; Akhbar al-Yawm 21/8/2025 (GN). The same Council nevertheless issued Decisions 1/2026 (25/2/2026, judicial law annulled), 2/2026 (26/2/2026), 4/2026 (26/3/2026, budget), 7/2026 (7/4/2026, parliamentary term extension upheld), 8/2026 (3/9/2026, Lebanese University law annulled), 9/2026 and 10/2026 (10/9/2026, social-security law annulled; general-amnesty Law 70/2026 suspended) — https://www.cc.gov.lb/ar/القرارات/قرارات-دستورية-القوانين/ (P). **Modelling: 10 seat nodes with `appointedBy` (Parliament ×5 / CoM ×5), tenure `status: expired_continuing` and a note that the continuation rule (members serve until successors take office) is UNVERIFIED as to its article.** Petitioners' names for 1/2026 are on the listing page (P) if needed for a `petitions` edge later. | P/GN |
| Special Tribunal for Lebanon (المحكمة الخاصة بلبنان) | UN Security Council Resolution 1757 (30/5/2007) and its annexed agreement; seat in Leidschendam (NL). | International tribunal applying Lebanese criminal law; **ceased operations on 31/12/2023** (UN News 31/12/2023 https://news.un.org/en/story/2023/12/1145217, S; Wikipedia summary https://en.wikipedia.org/wiki/Special_Tribunal_for_Lebanon, S). | Closed. Do **not** model as a live node; at most a `dissolved` historical node outside v1 scope (v1 history starts January 2025, decisions.md Q11). | S |

**Tax objection committees (لجان الاعتراض على الضرائب والرسوم) and the Conflicts Court (محكمة حل الخلافات)** appear on the Ministry's administrative-judiciary menu (P). The Conflicts Court (jurisdictional conflicts between ordinary and administrative courts; presided by the First President of Cassation with State Council and Cassation members — composition **UNVERIFIED**) is an ad hoc body; recommend one `court` node with `status: ad_hoc`.

## 3. Confessional (personal status) courts

### 3.1 Legal basis

| Text | What it does | Src |
|---|---|---|
| **Decree 60 L.R. of 13/3/1936** (High Commissioner's arrêté, "نظام الطوائف الدينية"), amended by Decree 146 L.R. of 18/11/1938 | Recognises the "historic" communities, gives each recognised community jurisdiction over the personal status of its members (Art. 2) and requires each to submit its personal-status code and procedure to the government and Parliament for ratification; Art. 25 protects the right to belong to no community (civil marriage abroad, "common law" community never organised). Still the base text — HRW, *Unequal and Unprotected*, 2015, section "Personal Status Laws and the State's Constitutional Order", https://www.hrw.org/report/2015/01/19/unequal-and-unprotected/womens-rights-under-lebanese-personal-status-laws (S, read 2026-09-18). | S |
| Decree 53 L.R. of 30/3/1939 | Exempts the Muslim communities from Decree 60 L.R. after Sunni objections (HRW, S). | S |
| **Law of 2/4/1951** (قانون تحديد صلاحيات المراجع المذهبية للطوائف المسيحية والطائفة الإسرائيلية) | Defines the judicial prerogatives of the Christian and Jewish communities' courts; Art. 33 required them to file their codes within a year — filed in 1951, never ratified by Parliament, applied anyway (HRW, S). | S |
| **Law of 16/7/1962** (قانون تنظيم القضاء الشرعي السني والجعفري) | Organises the Sunni and Ja'fari Sharia courts: first-instance single judges, a Supreme Sunni Court and a Supreme Ja'fari Court (chief judge + 2), judges appointed on the recommendation of the community's supreme Islamic authority with the approval of the Islamic judicial council (the Mufti, the presidents of the supreme courts, prosecution judges and inspectors); Art. 242 refers substantive law to the Hanafi and Ja'fari schools and, since amendment, to the resolutions of the Supreme Islamic Council for Sunnis (HRW, S). Law title and date as usually cited; **UNVERIFIED against the Official Gazette this session** (legallaw.ul.edu.lb unreachable). | S/U |
| Druze personal-status law of 24/2/1948; Law of 5/3/1960 organising the Druze courts | Codified Druze personal status; six first-instance Druze courts and a Supreme Appellate Court of two chambers in Beirut; judges appointed on the recommendation of the Minister of Justice after consulting Sheikh al-Aql / the Druze communal council (HRW, S). 1960 law date **UNVERIFIED**. | S/U |
| Codes of the Christian communities (1951 filings; Code of Canons of the Eastern Churches 1990; Latin Code 1983; synodal law of each Orthodox and Evangelical church) | Substantive and procedural law of the ecclesiastical courts (HRW, S). | S |
| Law 2002 (civil courts' jurisdiction on procedural matters such as personal-status enforcement) and Code of Civil Procedure Art. 95 (plenary Cassation on conflicts of jurisdiction) | Give the Court of Cassation limited oversight (conflicts of jurisdiction, denial of justice, breach of public order), not appellate review (HRW section "Court of Cassation Limited Oversight of Religious Courts", S). | S |

There is no civil personal-status law; the HRW report counts **15 separate personal-status laws** for the 18 recognised communities (HRW, S).

### 3.2 The courts

| Community group | Courts (structure) | Funding / relation to the State | Judges appointed by | Src |
|---|---|---|---|---|
| Sunni (المحاكم الشرعية السنية) | **19 first-instance courts** (single judges) + **Supreme Sunni Court** (Beirut, chief judge + 2 members) as appeal | **State-funded**; administratively attached to the Sunni supreme Islamic authority (Dar al-Fatwa / Supreme Islamic Sharia Council), itself linked to the Presidency of the Council of Ministers; judges are State-paid | Recommendation of the Supreme Islamic Sharia Council, approval of the Islamic judicial council; appointment by decree (HRW, S; instrument **UNVERIFIED**) | S |
| Ja'fari / Shia (المحاكم الشرعية الجعفرية) | **20 first-instance courts** + **Supreme Ja'fari Court** (Beirut, chief judge + 2) | State-funded; attached to the Supreme Islamic Shia Council | Same mechanism via the Supreme Islamic Shia Council (HRW, S) | S |
| Druze (المحاكم المذهبية الدرزية) | **6 first-instance courts** (single judges) + **Supreme Appellate Court** (Beirut, 2 chambers of 2 judges under one chief judge) | State-funded | Minister of Justice's recommendation after consulting Sheikh al-Aql and the Druze communal council (HRW, S) | S |
| Catholic churches (Maronite, Melkite, Armenian Catholic, Syriac Catholic, Chaldean, Latin) — المحاكم الروحية الكاثوليكية | First-instance courts in every eparchy plus a collegiate first-instance tribunal per church (3 judges + promoter of justice + defender of the bond); one appellate court per church; Roman Rota and Apostolic Signatura above | **Independently funded**; no State oversight | Patriarchs and bishops (Law of 2/4/1951 and canon law) (HRW, S) | S |
| Orthodox churches (Greek Orthodox, Armenian Orthodox, Syriac Orthodox, Assyrian, Coptic) — المحاكم الروحية الأرثوذكسية | First-instance sections (single judge) or chambers (chief + 2); one appellate court per church (chief + 2 senior judges); appellate rulings generally final | Independently funded | Church hierarchy (HRW, S) | S |
| Evangelical (المحكمة الإنجيلية) | One first-instance court and one appellate court (chief + 2, plus the Supreme Synod's legal counsellor) | Independently funded | Supreme Council of the Evangelical Community (HRW, S) | S |
| Jewish (المحكمة الإسرائيلية / الحاخامية) | Rabbinical court recognised under the Law of 2/4/1951; **effectively dormant** given the size of the community (S: general knowledge; **UNVERIFIED** whether any judge is currently appointed) | Independent | Community council | S/U |
| Alawite (العلويون) | Recognised community since Law 449/1995; adjudicates before the Ja'fari courts (S; **UNVERIFIED**) | — | — | U |

Counts for the graph: **19 + 20 + 6 = 45 State-funded first-instance confessional courts and 3 supreme courts** on the Muslim/Druze side; on the Christian side the number of ecclesiastical tribunals is a per-church matter (roughly one first-instance and one appellate tribunal per church, plus eparchial courts) and is **not enumerated in any public State source** found this session.

### 3.3 State courts versus community courts — how to model

1. **Two different legal natures.** The Sunni, Ja'fari and Druze courts are *State* courts in the organic sense: created by Lebanese law, staffed by judges paid from the State budget, with an Islamic judicial council and inspection, and (for Sunnis) their supreme council's decisions under the tutelage of the Council of Ministers. The Christian and Jewish courts are *community* courts whose jurisdiction is *recognised* by the State (Decree 60 L.R.; Law of 2/4/1951) but whose organisation, funding and judges are entirely internal to each church. HRW's sentence is the crisp version: "Christian courts, which are financially and administratively independent of state judicial bodies, are also independent from the state and receive little to no external oversight" (S).
2. **Recommendation.** Use the `confessional_court` subtype (decisions.md Q29) for *both* groups but with a mandatory attribute `stateFunded: true|false` and `establishedBy: law | recognition`. Model:
   - one node per State-organised jurisdiction: `Sunni Sharia courts` (with `firstInstanceCount: 19`), `Supreme Sunni Court`, `Ja'fari Sharia courts` (20), `Supreme Ja'fari Court`, `Druze courts` (6), `Druze Supreme Appellate Court` — 6 nodes;
   - one node per Christian church's tribunal system (Maronite, Melkite, Armenian Catholic, Syriac Catholic, Chaldean, Latin, Greek Orthodox, Armenian Orthodox, Syriac Orthodox, Assyrian, Coptic, Evangelical) and one for the Jewish court — 13 nodes, `stateFunded: false`, `status: recognised`;
   - do **not** model individual eparchial courts or the 45 single-judge seats.
3. **Edges.** `MinisterOfJustice —appoints→ DruzeCourts` (with `proposer: SheikhAlAql`); `SupremeIslamicShariaCouncil —advises→ CouncilOfMinisters —appoints→ SunniShariaCourts` (and the Shia equivalent); `Patriarch —appoints→ MaroniteTribunals` etc. The community authorities (Dar al-Fatwa, Supreme Islamic Shia Council, Druze communal council, patriarchates) are **not** State bodies and should be created as `advisory`-type nodes with `sector: confessional` only if the appointing edge is wanted; otherwise leave the appointing authority as a text attribute. `CourtOfCassation —oversees→ <every confessional court>` with `scope: jurisdictional_conflicts_only`.
4. **Placement.** Put the six State-organised confessional court nodes in the Judicial sector next to the ordinary courts (they are part of the State's judicial organisation and budget); put the thirteen recognised community tribunals on the outer ring of the Judicial sector, visually distinct (dashed border, `stateFunded: false`). This answers §6's question "do confessional courts belong in the graph": the State-organised ones do unambiguously; the recognised ones belong because they exercise exclusive jurisdiction over Lebanese citizens' personal status under a State text, but they must be rendered as *recognised*, not *created*.
5. **Officeholders.** Do not enter presidents of Sharia or ecclesiastical courts in v1; none was verified this session and the churn is untracked by the sources the changes-feed sweeps (decisions.md Q7).

## 4. Oversight and control bodies

Creating texts of Central Inspection, the Civil Service Board and the disciplinary councils: Part 01 §1.1. Each row answers: reports to, appointed by, current head, functioning.

| Body | Reports to / attached to | Head appointed by | Head as of 2026-09-18 | Functioning? | Src |
|---|---|---|---|---|---|
| Central Inspection (التفتيش المركزي) | Presidency of the Council of Ministers (Leg. Decree 115/1959 Art. 1: "أنشئ لدى رئاسة الوزارة"). Inspection Board = head + head of Research and Guidance + senior inspector general. Jurisdiction over all administrations, public institutions and municipalities; judiciary, army, ISF and General Security only in financial matters. Can sanction, refer to disciplinary councils, the Court of Audit and prosecutors (Art. 19) — https://www.cib.gov.lb/ar/إنشاء-التفتيش-المركزي (P). | Decree taken in the Council of Ministers (Art. 5). | **Judge Georges Auguste Attieh (جورج أوغست عطية)** — https://www.cib.gov.lb/ (P, "رئيس التفتيش المركزي: القاضي جورج أوغست عطية", read 2026-09-18). Appointed March 2017 (Lebanese Forces site 26/3/2017 "إحتفال بتعيين جورج عطية رئيساً للتفتيش المركزي", GN); still in post October 2025 (Al Akhbar 27/10/2025 "عطية «يستولي» على موظّفي الوزارات", GN). A swap of the CI and CSB heads was discussed in August 2024 (Kataeb 14/8/2024 "تبديل مركزي رئيسي مجلس الخدمة والتفتيش", GN) but the sites show no change. | Yes: 18 new administrative inspectors sworn in (cib.gov.lb news, P); audit missions (Beirut and Mount Lebanon Water, P). | P/GN |
| Civil Service Board (مجلس الخدمة المدنية) | Presidency of the Council of Ministers; Leg. Decree 114/1959; jurisdiction over all administrations, public institutions and municipalities except judiciary, army, ISF, General Security and State Security (Art. 1) — https://www.cib.gov.lb/ar/إنشاء-مجلس-الخدمة-المدنية (P). Under the 2025 appointments mechanism it shortlists three names per grade-one post (Part 01 §3.2). | Decree taken in the Council of Ministers on the PM's proposal (Art. 6); members by decree (Art. 7). | **Nisrine Machmouchi (نسرين مشموشي)**, president — https://www.csb.gov.lb/ar/ (P, "الرئيسة", read 2026-09-18). Appointed June 2020 (Lebanese Forces site 10/6/2020 "نسرين مشموشي رئيسة مجلس الخدمة المدنية", GN); met PM Salam on public-sector issues 20/1/2026 (Al Akhbar, GN); attended the 15/1/2026 cabinet session (Al-Markazia, P). Board members: **UNVERIFIED**. | Yes (competitions, shortlists, retirement-decree committee, Sept 2026, GN). | P/GN |
| Court of Audit — oversight role (ديوان المحاسبة) | Attached to the Presidency of the CoM; a-priori control of contracts and expenditure above thresholds, a-posteriori audit, judgment of accounts and of financial liability of officials; annual report (recipients: President, Parliament, PM — **article UNVERIFIED**) — https://www.coa.gov.lb/ar/عن-الديوان (P); Ministry of Justice explanatory pages (P, §2). | President and members by decree in the CoM (Part 01). | Judge Mohammad Badran (§2). | Yes; in July 2026 it joined a consultative meeting of the oversight bodies convened by the government (Al-Markazia 22/7/2026 "اجتماع تشاوري حول تطوير آليات وسبل العمل في الهيئات الرقابية"; Akhbar al-Yawm 30/7/2026; GN). | P/GN |
| Disciplinary councils (مجالس التأديب) | Leg. Decree 112/1959 Art. 57 — https://www.cib.gov.lb/ar/node/2005 (P, read 2026-09-18): (2) the council for **grade one and two employees, inspectors general and inspectors of Central Inspection** = "هيئة مجلس الخدمة المدنية ومن قاض من الفئة الثانية على الأقل تنتدبه وزارة العدلية ومن رتبة الموظف المحال ينتدبه رئيس مجلس الوزراء"; (4) the council for all other employees is formed by decree for one year on the CSB president's notice: a grade-two judge seconded by the Ministry of Justice (chair), three grade-two officials and one peer; government commissioner = head of Central Inspection or an inspector general. Art. 58: referral by decree or decision of the appointing authority, or by decision of the Central Inspection Board. | Ad hoc / annual decree. | n/a | Yes (statutory). | P |
| Higher Disciplinary Board (الهيئة العليا للتأديب) | Two bodies carry this name. (a) **For judges**: the disciplinary body under Leg. Decree 150/1983 (chapter on discipline, articles **UNVERIFIED**); it sat on the referral of Judge Ghada Aoun by the Public Prosecutor at Cassation in 2024 (Annahar 22/3/2024 "غادة عون تمثُل أمام الهيئة العليا للتأديب"; Kataeb 1/5/2024; GN) and Al Akhbar 21/8/2026 "«عقوبات تأديبية» لا تُؤدِّب القضاة المُرتكبين!" (GN) discusses its sanctions. (b) **For civil servants**: the judge-chaired board attached to the Presidency of the CoM referred to in Part 01 §1.1; its creating amendment is **UNVERIFIED** (not in the 1959 text as published by cib.gov.lb). | Judges' board: composed of senior judges by rank (ex officio) — **UNVERIFIED**. | **UNVERIFIED** (no name found). | (a) yes; (b) **UNVERIFIED**. | GN/U |
| Parliamentary oversight (الرقابة النيابية) | Const. Art. 37 (any deputy may raise the question of confidence; debate only after five days), Art. 66 (ministers' individual and collective responsibility), Arts. 70–71 (impeachment of PM and ministers by 2/3, trial before the Supreme Council), Art. 87 (final accounts of the budget submitted to Parliament) — Constitution PDF (P, Part 01). Rules of Procedure of 18/10/1994: written and oral questions, interpellations (استجواب) and confidence motions, parliamentary inquiry committees, standing committees (Part 01 §5.3) — https://www.lp.gov.lb/CustomPage.aspx?Id=7 (P; article numbers for questions/interpellations **UNVERIFIED** this session). | n/a | Speaker Nabih Berri (Part 01). | Yes, but the Supreme Council has never convened (Part 01) and general questions rarely lead to interpellation votes (S). | P/S |
| National Anti-Corruption Commission (الهيئة الوطنية لمكافحة الفساد) | Law 175 of 8/5/2020 (mentioned on the Commission's site, P: https://nacc.gov.lb/); independent authority with legal personality, financially and administratively autonomous; receives illicit-enrichment and asset declarations (Law 189/2020), investigates corruption, and is the appeal body under the Access to Information Law. Site menu: "الأعضاء", "كلمة رئيس الهيئة", "تصاريح الذمة المالية لأعضاء الهيئة" (P). | Six members appointed by decree taken in the Council of Ministers from candidates proposed by professional bodies and the HJC (Law 175 Arts. 6–8; **article numbers UNVERIFIED**); president elected among the members (**UNVERIFIED**). First bench appointed January 2022 (S; decree number **UNVERIFIED**). | **Judge Claude Karam (كلود كرم)**, president — https://nacc.gov.lb/ (P, "كلمة رئيس الهيئة: القاضي كلود كرم", read 2026-09-18). Members' names: **UNVERIFIED** (members page https://nacc.gov.lb/ar/الهيئة rendered without names in the cached copy). | Yes: 2025 annual report handed to President Aoun 8/7/2026 (Sawt Beirut International 8/7/2026; Alkalima 8/7/2026; GN); received by Speaker Berri 14/7/2026 (Elnashra, GN); sectoral corruption-risk working groups launched 10/8/2026 (Annahar, GN); decided access-to-information complaints, accepting 87% (Legal Agenda 12/8/2025, GN). | P/GN |
| Ombudsman — وسيط الجمهورية | Law 664 of 4/2/2005 creating the Mediator of the Republic (وسيط الجمهورية) attached to the Presidency of the CoM (S: law number and date as commonly cited; text not retrieved — legallaw.ul.edu.lb unreachable). | Appointed by decree in the Council of Ministers (S). | **Never appointed.** No Lebanese appointment appears in the news search (the RSS query "وسيط الجمهورية" returns only the Algerian institution, GN, checked 2026-09-18). Treat the office as `never_constituted`. | No. | S/U |
| Access to Information Law 28/2017 | Law 28 of 10/2/2017, amended by Law 233 of 30/7/2021; implementing Decree 6940 of 2020 (numbers **UNVERIFIED** against the Gazette); every administration must name an information officer and publish; refusals appealable to the NACC then the State Council (S). | n/a (obligation on every public body). | n/a; the NACC (above) is the appeal body. | **In force but poorly applied**: LBCI 17/12/2025 "حق الوصول إلى المعلومات في لبنان: قانون نافذ… وواقع معطّل" (GN); Gherbal Initiative hotline report March 2024–February 2026 (Al Jadeed 22/5/2026, GN); UNDP explainer 4/8/2022 (GN). The Ministry of Justice publishes its own access page https://www.justice.gov.lb/index.php/department-details/23/2 (P). | GN/P |

Other control bodies worth a node in the Independent & Regulatory sector but not researched here: Special Investigation Commission at BDL (هيئة التحقيق الخاصة, Law 318/2001; Part 02), Public Procurement Authority (هيئة الشراء العام, Law 244/2021; a vacancy at its head was reported: Al Akhbar 10/7/2026 "مَن المسؤول عن فراغ «الشراء العام»؟", GN), Banking Control Commission (Part 02), National Human Rights Commission (Part 02).

## 5. Security and military

All bodies below are `department` nodes with subtype `security_service`, sector **Executive** (see §6). Appointment path for every chief is a decree taken in the Council of Ministers by 2/3 on the competent minister's proposal (Part 01 §3.1); the 13/3/2025 package (army commander and the three DGs) is the reference event (Kataeb 13/3/2025 "صدور مراسيم الترقية والتعيين لقادة الأجهزة الأمنية الجدد"; Sawt Beirut International 13/3/2025 "بالأسماء.. مجلس الوزراء يقرّ رسميًا التعيينات الأمنيّة والعسكريّة"; Anadolu 13/3/2025; GN).

### 5.1 Bodies and current holders

| Body | Legal basis / parent | Structure | Holder(s) as of 2026-09-18 | Confessional custom (Part 01 §4.3) | Src |
|---|---|---|---|---|---|
| Lebanese Armed Forces — Army Command (قيادة الجيش) | National Defence Law, Leg. Decree 102 of 16/9/1983, Art. 16: the Ministry of National Defence comprises the Army, the Directorate General of Administration, the General Inspectorate and the Military Council — https://www.lebarmy.gov.lb/ar/content/المجلس-العسكري-في-وزارة-الدفاع-الوطني-اللبناني (P). Commander appointed "بمرسوم يتخذ في مجلس الوزراء بناء على اقتراح وزير الدفاع الوطني", holds the rank of General (عماد) and "يرتبط مباشرة بوزير الدفاع"; Chief of Staff likewise, after consulting the Commander, and deputises for him — https://www.lebarmy.gov.lb/ar/content/قيادة-الجيش (P). Commander-in-chief = President (Const. Art. 49). | Commander; Staff (Chief of Staff, deputy chiefs of staff, directorates incl. Intelligence, Operations, Personnel…); regional commands, brigades, regiments, air force, navy (lebarmy.gov.lb structure menu, P). ~90,000 personnel (S: Wikipedia). | **Commander: General Rodolphe Haykal (رودولف هيكل), since 13/3/2025** — https://www.lebarmy.gov.lb/ar/army_commanders (P: row "رودولف هيكل | عماد | 13/03/2025 | -"; predecessor Joseph Aoun 8/3/2017–9/1/2025). **Chief of Staff: Maj. Gen. Hassan Audi (حسان عوده), since 8/2/2024** — https://www.lebarmy.gov.lb/ar/chiefs_of_staff (P: "حسان عوده | لواء ركن | 08/02/2024 | -"; predecessor Amin al-Arm 4/4/2019–25/12/2022; the post was vacant Dec 2022–Feb 2024). | Commander Maronite; Chief of Staff Druze (S: Wikipedia LAF article citing Civil Society Knowledge Centre; Part 01 §4.3). | P |
| Military Council (المجلس العسكري) | Defence Law Arts. 26–28 (P, lebarmy magazine no. 336, June 2013, same URL). | **Art. 26: Army Commander (president), Chief of Staff (vice-president), Director General of Administration, Inspector General, Secretary General of the Higher Defence Council, and one general officer appointed by decree in the CoM on the Defence Minister's proposal after consulting the Commander** — six members. Art. 27: approves organisation of the MoD's institutions, senior postings (issued by decree or ministerial decision), officer promotions from captain, referrals of officers to the disciplinary council, procurement specifications, military-academy intake. | Decrees appointing and promoting the members were issued on **3/4/2025** (Elnashra 3/4/2025 "رئاسة الجمهورية: صدور مراسيم تعيين وترقية أعضاء المجلس العسكري", GN). **Names of the DG Administration, Inspector General and sixth member: UNVERIFIED.** | Custom: the six seats split Maronite (Commander), Druze (CoS), Sunni, Shia, Greek Orthodox, Greek Catholic (S: Wikipedia citing CSKC). | P/GN |
| Directorate of Intelligence (مديرية المخابرات) | A directorate of the Army Staff under the Commander; role limited to military security by the Taif document (lebarmy.gov.lb feature "مديرية المخابرات", P: https://www.lebarmy.gov.lb/ar/content/مديرية-المخابرات). Director named by the Military Council on the Commander's proposal (Art. 27, P). | Branches (counter-terrorism and espionage, regional intelligence branches, etc.) (P, feature). | **Brig. Gen. Antoine (Tony) Kahwaji (أنطوان قهوجي)** was named director by the Military Council on 19/1/2025 (Al-Markazia 19/1/2025 "المجلس العسكري ينهي بتعيين العميد الركن انطوان قهوجي مديراً للمخابرات", GN), having first been named on 30/11/2021 (Elnashra, GN). Contacts to fill the post again were reported on 14/3/2025 (Al Akhbar "اتصالات لحسم منصبَيْ مدير المخابرات ورئيس المعلومات", GN); 2026 coverage refers to "مدير المخابرات في الجيش" without a name. **Current holder UNVERIFIED.** | Custom: Maronite (S). | GN/U |
| Internal Security Forces (قوى الأمن الداخلي) | Law 17 of 6/9/1990 (organisation of the ISF); under the Ministry of Interior and Municipalities. DG appointed by decree in the CoM on the Interior Minister's proposal (Part 01). | Directorate General; **Command Council (مجلس القيادة)**; General Inspectorate (المفتشية العامة); Staff (هيئة الأركان); Information Branch (شعبة المعلومات); ISF Institute; territorial units (Beirut Police, Regional Gendarmerie, Judicial Police, Embassies Security…) — https://www.isf.gov.lb/ar (P, menu and news of 2026 naming the heads of the Staff unit, Institute, and branches). Composition of the Command Council (Law 17/1990 article): **UNVERIFIED**. | **DG: Maj. Gen. Raed Abdallah (رائد عبد الله)** — https://www.isf.gov.lb/ar/page/28 (P, "أنشطة المدير العام… اللواء رائد عبد الله", read 2026-09-18); appointed 13/3/2025 (Annahar 13/3/2025 "من هو مدير عام قوى الأمن الداخلي اللواء رائد عبدالله؟", GN; Wikipedia ISF list, S). Predecessor Imad Othman 2017–2025 (S). | DG Sunni (S). | P/GN |
| General Security (المديرية العامة للأمن العام) | Decree-Law 139 of 12/6/1959 (attaches General Security to the Minister of Interior under a DG) and organisational Decree 2873 of 16/12/1959 (S: Wikipedia GDGS article quoting the texts). Handles residence, passports, border control, censorship, intelligence. Site https://www.general-security.gov.lb/ar (P). | DG; regional departments, border, maritime and airport posts (S). | **DG: Maj. Gen. Hassan Choucair (حسن شقير)**, appointed 13/3/2025 (Kataeb/Sawt Beirut 13/3/2025 package, GN; Wikipedia, S: "Hassan Choucair … General Director"; predecessor Elias Baissari acting DG 3/3/2023–17/3/2025, Abbas Ibrahim 2011–2023). The site's "نشاطات المدير العام" shows activity to 15/9/2026 without naming him (P). | DG Shia since 1998 (S: Wikipedia). | P/S/GN |
| State Security (المديرية العامة لأمن الدولة) | Defence Law Art. 7 as amended by Leg. Decrees 1/1984 and 39 of 23/3/1985: "تنشأ لدى المجلس [الأعلى للدفاع] مديرية عامة تسمى «المديرية العامة لأمن الدولة» خاضعة لسلطة المجلس وتابعة لرئيسه ونائب رئيسه" (ar.wikipedia article on the HDC quoting the law, S; Part 01). Reports to the President and PM through the HDC. | DG, Deputy DG, DG's office, eight regional directorates (S: Wikipedia State Security article). | **DG: Maj. Gen. Edgard Lawandos (إدغار لاوندس)**, appointed at the cabinet session of 13/3/2025 (L'Orient Today 14/3/2025 https://today.lorientlejour.com/article/1451816/general-nicolas-tabet-new-head-of-the-southern-litani-sector.html, S; Wikipedia, S); active as "اللواء الركن لاوندس" in April 2026 and as "المدير العام لأمن الدولة" on 24/8/2026 (Akhbar al-Yawm 23/4/2026; Elnashra 24/8/2026; GN). Predecessor Tony Saliba 8/3/2017–13/3/2025 (S). **Deputy DG: UNVERIFIED** (no name found). | DG Greek Catholic (S: Wikipedia); Deputy DG Shia by custom (Part 01 §4.3, S). | S/GN |
| Customs (الجمارك اللبنانية) | Customs Law (Leg. Decree 4461 of 15/12/2000 — **UNVERIFIED**); under the Ministry of Finance; two organs: the **Higher Council of Customs** (المجلس الأعلى للجمارك, president + 2 members, regulatory and disciplinary role) and the **Directorate General of Customs** (المديرية العامة للجمارك) — https://www.customs.gov.lb/ (P, about page: "إدارة عامة مسؤولة عن استيفاء الرسوم الجمركية…"; council page returned 404). | Higher Council (3) + DG + regional directorates and the Beirut port/airport directorates (P/S). | **Higher Council president: Brig. Gen. Misbah Khalil Khalil (مصباح خليل خليل); members Louay al-Hajj Shehadeh (لؤي الحاج شحادة) and Charbel Nassib Khalil (شربل نسيب خليل); DG: Gracia Youssef al-Kazzi (غراسيا يوسف القزي)** — all appointed by the Council of Ministers on **15/1/2026** — Al-Markazia 15/1/2026 https://almarkazia.com/ar/جلسة-مالية-لمجلس-الوزراء-في-السراي-1 (P, cabinet-session report: "تم تعيين العميد مصباح خليل خليل رئيسا للمجلس الأعلى للجمارك والسيدين لؤي الحاج شحادة وشربل نسيب خليل عضوين، إضافة إلى تعيين السيدة غريسيا يوسف القزي مديرة عامة للجمارك"). Predecessor DG Badri Daher (arrested after the 2020 port explosion; S: Wikipedia). | Higher Council president Shia; DG Maronite (S: custom as commonly reported; **UNVERIFIED**). | P |
| Civil Defence (المديرية العامة للدفاع المدني) | Under the Ministry of Interior and Municipalities; creating text **UNVERIFIED** (site https://www.civildefense.gov.lb/ blocked by a captcha this session). A **Higher Council of Civil Defence** (المجلس الأعلى للدفاع المدني) chaired by the Interior Minister exists (Fana News / Akhbar Hayat 16/9/2026 "وزير الداخلية يترأس اجتماعا للمجلس الأعلى للدفاع المدني", GN). | DG; regional centres; volunteers. | **DG: Brig. Gen. Imad Khreich (عماد خريش)** — named acting DG by Interior Minister Ahmad Hajjar on 14/8/2025 (Elnashra 14/8/2025 "الحجار عيّن عماد خريش مديرًا عامًا جديدًا للدفاع المدني"; Kataeb 14/8/2025 "العميد الركن عماد خريش مديرًا عامًا للدفاع المدني"; GN), then **appointed full DG by the Council of Ministers on 30/1/2026** (Al-Markazia 30/1/2026 "تعيين عماد خريش مديرا عاما اصيلا للدفاع المدني"; Yasour 30/1/2026; Lebanon 24 30/1/2026 cabinet round-up; GN). Active September 2026 (Elnashra 16/9/2026; Al-Markazia 16/9/2026; GN). Predecessor Raymond Khattar (S: Wikipedia, dated). | Maronite by custom (S, **UNVERIFIED**). | GN |
| Higher Defence Council (المجلس الأعلى للدفاع) | Const. Arts. 49, 64; Defence Law Arts. 7–9 (Part 01). | **President (chair), PM (vice-chair), Ministers of Defence, Foreign Affairs, Finance, Interior, Economy**; other ministers may be added by decree in the CoM; the chair may summon anyone; **Secretary General** = officer of colonel rank or above appointed by decree in the CoM on the PM's and Defence Minister's proposal; decisions confidential — https://presidency.gov.lb/defense-council (P, Part 01); ar.wikipedia HDC article quoting the law (S) adds that in practice the security chiefs and the Public Prosecutor at Cassation attend. | Chair: President Joseph Aoun (ex officio); vice-chair: PM Nawaf Salam (ex officio). **Secretary General: Maj. Gen. Mohammad al-Mustafa (محمد المصطفى)** — first appointed as Brig. Gen. on 22/3/2022 (Lebanese Forces site 22/3/2022 "تعيين العميد الركن محمد المصطفى أميناً عاماً للمجلس الأعلى للدفاع", GN); NNA 31/12/2025 "الجيش: تعيين العميد الركن محمد المصطفى أمينا عاما للمجلس الأعلى للدفاع وترقيته إلى رتبة لواء ركن" (GN) reports a renewed appointment with promotion to Major General (article body not read; the December 2025 date should be confirmed against the decree before publishing). | SG Shia by custom (Part 01 §4.3). | P/GN |

### 5.2 Which subtype and sector

- All eight are `department` / `security_service`. The LAF, ISF, General Security, Customs and Civil Defence hang under a ministry (`Ministry —administers→ Service`); State Security hangs under the HDC (`HDC —administers→ StateSecurity`), and the HDC itself is a `commission` chaired ex officio by the President. They belong in the **Executive sector**, in an inner "security ring" between the Council of Ministers pill and the ministries, so that the `appoints` edges from the Council of Ministers are short and the `ex_officio` edges from President and PM to the HDC are visible.
- The Military Court is a `court` node in the Judicial sector but with `administers` from the Ministry of National Defence, which is the single clearest cross-sector edge in the graph and worth showing.
- The Military Council and the Directorate of Intelligence are sub-units; model the Military Council as a `commission` (six seat nodes with confessional allocation and `ex_officio` edges from Commander, CoS, SG-HDC, IG, DG-Admin) because its seats are the most contested confessional allocation outside the cabinet; leave the Directorate of Intelligence as a `dept_head` position under the Army Command, not a body.
- Tenure records to seed (all with `since` dates above): Haykal 2025-03-13; Audi 2024-02-08; Abdallah 2025-03-13; Choucair 2025-03-13; Lawandos 2025-03-13; Khalil and al-Kazzi 2026-01-15; Khreich 2025-08-14 (acting) → 2026-01-30 (full); al-Mustafa 2022-03-22 (and 2025-12-31 to confirm). All decree numbers are **UNVERIFIED** and must be pulled from the Official Gazette or pcm.gov.lb before publishing.

## 6. Implications for the civicleb model

1. **The four-sector layout holds, with one relabelling.** Every body in this part fits Legislative / Executive / Judicial / Independent & Regulatory except the oversight bodies attached to the Presidency of the Council of Ministers (Central Inspection, Civil Service Board, Court of Audit, Higher Disciplinary Board). They are organically executive but functionally control bodies. Recommendation: keep them in the **Independent & Regulatory** sector under a sub-band "Oversight and control" together with the NACC, the (never-constituted) Ombudsman and the Constitutional Council, and draw their `administers`/`appoints` edges back to the Council of Ministers. The Court of Audit is the hard case: it is a court (financial jurisdiction) *and* an oversight body; place it on the boundary between the Judicial and Independent sectors and give it subtype `court` with a `functions: [audit, jurisdiction]` attribute.
2. **Security services belong in the Executive sector, not in a fifth sector.** Nothing in Lebanese law makes them independent: each is attached to a minister or to the HDC, and every chief is appointed by the Council of Ministers. A fifth "Security" sector would misstate this and hide the `appoints` edges that are the single most political act of a new government (the 13/3/2025 package). Render them as an inner ring of the Executive sector, colour-coded by ministry.
3. **Confessional courts belong in the graph, but in two classes** (§3.3): six State-organised Sharia/Druze court nodes in the Judicial sector proper, thirteen recognised community tribunals on its outer edge with `stateFunded: false`. Without them the Judicial sector would omit the courts that decide marriage, divorce, custody and inheritance for every citizen.
4. **Edge types missing from the CivLab vocabulary** (in addition to Part 01 §3.4):
   - `prosecutes` — Public Prosecution at Cassation → every court it prosecutes before; Government Commissioner → Military Court; Financial Prosecutor → Court of Audit referrals. Directional and different from `oversees`.
   - `inspects` — Central Inspection → all administrations and municipalities (financial-only flag for judiciary, army, ISF, GS); Judicial Inspection → judges; ISF General Inspectorate → ISF units; MoD Inspector General → army. Without it the oversight bodies float unconnected.
   - `disciplines` — disciplinary councils / Higher Disciplinary Board → categories of officials; HJC → judges; Military Council → officers (Art. 27). Could be folded into `inspects` with a `power: sanction` attribute, but a separate verb is clearer for the public audience.
   - `refers_to` (or `petitions`) — Council of Ministers → Judicial Council (referral by decree); deputies → Constitutional Council (10 signatures); Central Inspection → Court of Audit / prosecution. Needed for the 2026 story of Decision 1/2026.
   - `reviews` — Constitutional Council → laws (with outcome attribute `annulled | rejected | suspended`), Court of Cassation → confessional courts (`scope: jurisdiction_only`), State Council → administrative acts. `oversees` is too broad for these.
   - `commands` — President → LAF (Art. 49 commander-in-chief) versus Minister of Defence → Army Commander (administrative link). `administers` cannot carry both.
   - `chairs` as a specialisation of `ex_officio` is not needed; use `ex_officio` with `role: chair`.
5. **Statuses the schema must carry**: `never_constituted` (Ombudsman, Senate, Art. 95 committee, Supreme Council in practice), `expired_continuing` (Constitutional Council bench, 2025–), `acting` (Civil Defence DG Aug 2025–Jan 2026; prosecutor at Cassation Apr 2026), `ad_hoc` (Conflicts Court, disciplinary councils), `dissolved` (STL). These are tenure/body statuses, not edge attributes.
6. **Law-in-force flag.** The annulment of Law 36/2026 shows that the "legal basis" field on a node must point to a *version* of a text with `inForce: true|false` and a `supersededBy`/`annulledBy` link; otherwise the HJC node would have been re-modelled in January 2026 and reverted in February.
7. **Node budget for this part** (v1): ordinary judiciary 24; administrative/financial/exceptional 8 (State Council, Bureau, Court of Audit, Judicial Council, Military Court, Military Cassation, Supreme Council, Constitutional Council) + 10 CC seats; confessional 19; oversight 9 (CI, CSB, disciplinary councils, Higher Disciplinary Board, NACC, Ombudsman, Public Procurement Authority, Special Investigation Commission, National Human Rights Commission); security 8 bodies + 6 Military Council seats + ~10 head positions. **About 95 nodes and roughly 45 named tenures**, of which 14 officeholders are verified above and the rest are marked UNVERIFIED.
8. **Sources policy.** For this part the reliable primary sources are the Ministry of Justice judicial map, cc.gov.lb, cib.gov.lb, csb.gov.lb, nacc.gov.lb, isf.gov.lb, lebarmy.gov.lb and al-Markazia's cabinet-session reports; the Google News RSS feed is only a locator. Before publishing any tenure from §1–§5, pull the decree from the Official Gazette (Legal Agenda's judicial observatory, https://legal-agenda.com, is the best secondary index for judicial decrees; it is Cloudflare-gated and could not be fetched this session).

### Open items (UNVERIFIED list)

- Text and reasoning of Constitutional Council Decision 1/2026 (full annulment confirmed by listing and press; whether any article survived).
- Names in Decree 823/2025 (Cassation chamber presidents, first presidents of appeal, appellate prosecutors) and in the Judicial Council decree of 12/9/2025; whether the September 2026 partial permutations decree was issued.
- Decree numbers and dates for: Abboud (2019), Gemayel (State Council), Badran (Court of Audit), al-Hajj and Mneimneh (30/4/2026), Attieh (2017), Machmouchi (2020), Karam and NACC members (2022), Haykal/Abdallah/Choucair/Lawandos (13/3/2025), Khalil/al-Kazzi (15/1/2026), Khreich (30/1/2026), al-Mustafa (2022 and 31/12/2025).
- Current Directorate of Intelligence director; Deputy DG State Security; Military Council members (3/4/2025 decrees); Military Cassation Court president (March 2026); whether Brig. Gen. Fayyad still presides the Military Court; ISF Command Council composition (Law 17/1990); creating texts of Civil Defence and of the Higher Council of Customs; the Higher Disciplinary Board's creating amendment and chair.
- Article-level citations for Leg. Decree 150/1983 (Cassation, Judicial Inspection, discipline), Law 328/2001 (prosecution hierarchy), Leg. Decree 82/1983 (Court of Audit composition and report recipients), Law 175/2020 (NACC composition) and Law 664/2005 (Ombudsman).
- Official Gazette references for the Law of 16/7/1962 (Sharia courts), the Druze courts law of 1960 and the Law of 2/4/1951.

---


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

---
