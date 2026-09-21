import json, sys, math
D=sys.argv[1]; g=json.load(open(f"{D}/graph.json")); N=g['nodes']
C={'legislative':'#e4573d','executive':'#8484dc','judicial':'#c89c15','independent':'#58b5a2'}
SEC={'legislative':'Legislative','executive':'Executive','judicial':'Judicial','independent':'Independent & regulatory'}
CSS='''<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
html,body{margin:0;background:#161310;color:#ede9e2;font-family:Inter,system-ui,sans-serif;width:390px;-webkit-font-smoothing:antialiased}
.wrap{padding:12px}
.card{background:#1f1b18;border-radius:16px}
.hdr{display:flex;gap:8px;margin-bottom:12px}.hdr .card{flex:1;height:48px;display:flex;align-items:center;padding:0 16px;font-weight:600;font-size:15px;gap:8px}.hdr .sq{width:48px;flex:none;justify-content:center;padding:0}
.muted{color:#a29b90;font-weight:400}
h2{font-size:20px;margin:0 0 4px}.sub{color:#cfc9c0;font-size:14px;margin:0 0 12px}
.mono{font-family:ui-monospace,Menlo,monospace;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#a29b90}
.row{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:12px;background:#262220;margin-bottom:8px}
.row .n{flex:1;min-width:0}.row .t{font-size:16px;font-weight:600}.row .s{font-size:13px;color:#a29b90;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.row .c{font-size:15px;color:#cfc9c0;font-variant-numeric:tabular-nums}
.chev{color:#a29b90}
.dot{width:12px;height:12px;border-radius:50%;flex:none}
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
.cell{background:#262220;border-radius:12px;padding:10px 8px;text-align:center}
.cell svg{display:block;margin:0 auto 6px}.cell .t{font-size:12px;line-height:1.25;font-weight:500}.cell .s{font-size:11px;color:#a29b90;margin-top:2px}
.crumb{font-size:13px;color:#a29b90;margin-bottom:10px}.crumb b{color:#ede9e2;font-weight:500}
</style>'''
def glyph(kind,color,r=14,x=None,y=None):
    s=r/7
    if kind in('elected','constituency'): shp='<circle cx="10.5" cy="11" r="7"/>'
    elif kind=='commission': shp='<rect x="2.51" y="11" width="11.3" height="11.3" rx="4.08" transform="rotate(-45 2.51 11)"/>'
    elif kind=='court': shp='<polygon points="10.5,4 17.16,8.84 14.61,16.66 6.39,16.66 3.84,8.84"/>'
    elif kind=='security': shp='<path d="M10.5 3.5 17 6.2v4.6c0 3.4-2.8 6.2-6.5 7.7C6.8 17 4 14.2 4 10.8V6.2l6.5-2.7Z"/>'
    elif kind=='regulator': shp='<polygon points="13.18,4.53 16.97,8.32 16.97,13.68 13.18,17.47 7.82,17.47 4.03,13.68 4.03,8.32 7.82,4.53"/>'
    elif kind=='head': shp='<rect x="4.25" y="8" width="12.5" height="10" rx="2.6"/><circle cx="10.5" cy="8" r="3.75"/>'
    else: shp='<rect x="4.67" y="4.67" width="11.66" height="11.66" rx="3.32"/>'
    layers=''.join(shp.replace('/>',f' {a}/>') for a in ['fill="#272220"',f'fill="{color}" fill-opacity="0.5"',f'fill="none" stroke="{color}" stroke-width="{1.1/s:.2f}"'])
    size=2*r+4; pos=f' x="{x}" y="{y}"' if x is not None else ''
    return f'<svg{pos} width="{size}" height="{size}" viewBox="{-r-2} {-r-2} {size} {size}"><g transform="scale({s:.3f}) translate(-10.5,-11)">{layers}</g></svg>'
def kind(n):
    st=n.get('subtype'); t=n['type']
    if t=='department' and st in('court','confessional_court'): return 'court'
    if t=='department' and st=='security_service': return 'security'
    if t=='commission' and st=='regulator': return 'regulator'
    if t=='dept_head': return 'head'
    return t
def hdr(extra=''):
    return f'<div class="hdr"><div class="card"><span style="color:#6fbf95">&#10038;</span> Civic Leb <span class="muted">/ Lebanon{extra}</span></div><div class="card sq">&#8981;</div></div>'
def page(body): return f'<html><head><meta charset="utf-8">{CSS}</head><body><div class="wrap">{body}</div></body></html>'

# A1 sectors
bysec={}
for n in N.values():
    if n['type'] in('dept_head','seat'): continue
    bysec.setdefault(n.get('sector') or 'none',[]).append(n)
b=hdr()+'<div class="card" style="padding:16px 14px 8px"><h2>The Lebanese state</h2><p class="sub">Browse by branch, then by group.</p>'
for sid in ['legislative','executive','judicial','independent']:
    heads=sum(1 for n in N.values() if n['type']=='dept_head' and n.get('sector')==sid)
    b+=f'<div class="row"><span class="dot" style="background:{C[sid]}"></span><div class="n"><div class="t">{SEC[sid]}</div><div class="s">{len(bysec.get(sid,[]))} bodies · {heads} offices</div></div><span class="chev">&#8250;</span></div>'
b+='</div><div class="card" style="padding:14px;margin-top:12px"><div class="mono" style="margin-bottom:8px">Highest offices</div>'
for slug in ['lb-president-of-the-republic','lb-prime-minister','lb-speaker-of-parliament']:
    n=N[slug]; who=next((p for p in n['people'] if p['name']),None)
    b+=f'<div class="row" style="margin-bottom:6px">{glyph("head",C[n["sector"]],11)}<div class="n"><div class="t">{n["name"]["en"]}</div><div class="s">{who["name"]["en"] if who else "vacant"}</div></div><span class="chev">&#8250;</span></div>'
b+='</div>'
open(f"{D}/A1-sectors.html","w").write(page(b))

# A2 groups
ex=[n for n in N.values() if n.get('sector')=='executive' and n['type']!='dept_head']
groups=[('Council of Ministers',[n for n in ex if (n.get('layoutHints') or {}).get('pill')=='cabinet'],'commission'),
        ('Security services',[n for n in ex if n.get('subtype')=='security_service'],'security'),
        ('Presidency & top offices',[n for n in ex if n['id'] in('lb-presidency-of-the-republic','lb-presidency-of-the-council-of-ministers','lb-council-of-ministers','lb-higher-defence-council')],'elected'),
        ('Public institutions & companies',[n for n in ex if n.get('subtype') in('public_institution','state_company','advisory_body','directorate_general')],'department')]
b=hdr(" / Executive")+f'<div class="card" style="padding:16px 14px 8px"><div class="crumb"><b>Executive</b> · {len(ex)} bodies</div>'
for title,lst,k in groups:
    b+=f'<div class="row">{glyph(k,C["executive"],12)}<div class="n"><div class="t">{title}</div><div class="s">{", ".join(n["name"]["en"] for n in lst[:3])}{"…" if len(lst)>3 else ""}</div></div><span class="c">{len(lst)}</span><span class="chev">&#8250;</span></div>'
b+='</div>'
open(f"{D}/A2-groups.html","w").write(page(b))

# A3 members
cab=sorted(groups[0][1],key=lambda n:n['name']['en'])
b=hdr(" / Executive")+f'<div class="card" style="padding:16px 14px 14px"><div class="crumb"><b>Council of Ministers</b> · {len(cab)} ministries</div><div class="grid">'
for n in cab:
    who=next((p for p in n['people'] if p['name']),None)
    nm=n['name']['en'].replace('Ministry of ','').replace('Office of the Minister of State for ','')
    b+=f'<div class="cell">{glyph("department",C["executive"],13)}<div class="t">{nm}</div><div class="s">{who["name"]["en"] if who else ""}</div></div>'
b+='</div></div>'
open(f"{D}/A3-members.html","w").write(page(b))

# C neighbourhood
me=N['lb-ministry-of-finance']; E=g['edges']
verbs={'oversees':'oversees','inspects':'inspects','appoints':'appoints','tutelage':'tutelage over','elects':'elects','ex_officio':'ex officio on','confirms':'confirms','advises':'advises','commands':'commands'}
fam={'oversees':'#58b5a2','inspects':'#58b5a2','appoints':'#cfc9c0','tutelage':'#8484dc','elects':'#e4573d','ex_officio':'#a29b90','confirms':'#e4573d'}
items=[]
for eid in me['edges']:
    e=E.get(eid)
    if not e or e['type']=='dept_head': continue
    other=e['toId'] if e['fromId']==me['id'] else e['fromId']
    if other in N: items.append((e['type'],other))
for c in me.get('children',[]): items.append(('structure',c))
W,H=366,340; cx,cy=W/2,H/2+8; n=len(items); R=118
svg=f'<svg width="{W}" height="{H}" viewBox="0 0 {W} {H}" style="display:block">'
for i,(t,other) in enumerate(items):
    a=-math.pi/2+2*math.pi*i/n; x,y=cx+math.cos(a)*R, cy+math.sin(a)*R
    col=fam.get(t,'#a29b90') if t!='structure' else '#8484dc'
    dash=' stroke-dasharray="2 3"' if t=='structure' else ''
    svg+=f'<line x1="{cx}" y1="{cy}" x2="{x:.0f}" y2="{y:.0f}" stroke="{col}" stroke-width="1.5"{dash}/>'
    mx,my=(cx+x)/2,(cy+y)/2; label=verbs.get(t,t) if t!='structure' else 'part of it'
    svg+=f'<rect x="{mx-32:.0f}" y="{my-9:.0f}" width="64" height="18" rx="5" fill="#1f1b18" stroke="{col}" stroke-opacity=".6"/><text x="{mx:.0f}" y="{my+4:.0f}" text-anchor="middle" font-size="9.5" fill="{col}" font-family="Inter">{label}</text>'
    on=N[other]; k=kind(on); c2=C.get(on.get('sector') or 'executive','#a29b90')
    svg+=glyph(k,c2,12,x=f"{x-16:.0f}",y=f"{y-16:.0f}")
    nm=on['name']['en']; nm=nm if len(nm)<=24 else nm[:23]+'…'
    ty=y+(30 if y>cy else -22)
    svg+=f'<text x="{x:.0f}" y="{ty:.0f}" text-anchor="middle" font-size="10" fill="#cfc9c0" font-family="Inter">{nm}</text>'
svg+=glyph("department",C["executive"],20,x=f"{cx-24:.0f}",y=f"{cy-24:.0f}")
svg+=f'<text x="{cx}" y="{cy+40}" text-anchor="middle" font-size="12" font-weight="600" fill="#8484dc" font-family="Inter">Ministry of Finance</text></svg>'
b=hdr(" / Executive")+f'<div class="card" style="padding:10px 10px 4px"><div class="mono" style="padding:4px 6px 0">Who is connected</div>{svg}</div>'
b+='<div class="card" style="padding:16px 14px;margin-top:12px"><h2 style="font-size:24px">Ministry of Finance</h2><div class="muted" style="font-size:13px">وزارة المالية</div><div style="margin-top:12px" class="row">'+glyph('head',C['executive'],11)+'<div class="n"><div class="t">Yassine Jaber</div><div class="s">Minister of Finance · In office 2025 · Amal Movement</div></div></div></div>'
open(f"{D}/C-neighbourhood.html","w").write(page(b))
print("mocks written")
