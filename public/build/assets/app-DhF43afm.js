const __vite__mapDeps=(i,m=__vite__mapDeps,d=(m.f||(m.f=["assets/Login-DXAJ89uc.js","assets/runtime-dom.esm-bundler-eRQWhJR1.js","assets/index.esm-DjEY3gmd.js","assets/Index-D0lVKLhi.js","assets/PageHeader-CpwiNehB.js","assets/Toggle-jpjBnkxD.js","assets/EmptyState-DPoe8zku.js","assets/pencil-BsgmCD4l.js","assets/trash-2-CowrgnPj.js","assets/ellipsis-DTgl3cQv.js","assets/Index-Dni9Z1bG.js","assets/LoanLedger-HuAOVKs2.js","assets/StatCard-zTW1hVvd.js","assets/SearchInput-CvPlrEDg.js","assets/LedgerPeriodFilter-SwuTXNjF.js","assets/useSelection-DzcF0xGE.js","assets/arrow-up-DekuZ07R.js","assets/chevron-left-hTT7jEVJ.js","assets/chevron-right-Dbk38bOb.js","assets/ProgressBar-CuQFh-hP.js","assets/Index-Dpz7nxKo.js","assets/Chart-CNpSK654.js","assets/Index-CpYJdiUk.js","assets/IconColorPicker-C4j7-W-w.js","assets/Dashboard-C_FGoJxY.js","assets/BarList-Cp1sHD2D.js","assets/Index-DZEAz6N4.js","assets/PeriodFilter-CD6e5PlO.js","assets/download-CFMYmFyq.js","assets/Index-B-Ip2V1J.js","assets/Index-BnT7QfBs.js","assets/Index-COQz6pep.js","assets/Form-BZG6OrxU.js","assets/InvoiceStatusBadge-DJzPqsj8.js","assets/Index-D-Fq7-Hw.js","assets/Index-i3ohnERy.js","assets/Index-DF9wI4je.js","assets/Index-BloN52_X.js"])))=>i.map(i=>d[i]);
import{h as Q,o as le,a as X,b as i,c as y,r as T,n as C,d as _,e as D,f as ie,g as z,i as s,j as H,u as n,t as b,k as x,F as I,T as be,w as ce,l as ae,m as E,p as q,q as we,s as F,v as W,x as Me,y as P,z as _e,A as Be,B as Ne,C as ze,D as Oe,E as Fe}from"./runtime-dom.esm-bundler-eRQWhJR1.js";import{Q as R,T as Ue,N as ue,i as ee,j as Re}from"./index.esm-DjEY3gmd.js";const Ge="modulepreload",Ke=function(e){return"/build/"+e},pe={},A=function(t,r,l){let o=Promise.resolve();if(r&&r.length>0){document.getElementsByTagName("link");const c=document.querySelector("meta[property=csp-nonce]"),p=(c==null?void 0:c.nonce)||(c==null?void 0:c.getAttribute("nonce"));o=Promise.allSettled(r.map(u=>{if(u=Ke(u),u in pe)return;pe[u]=!0;const d=u.endsWith(".css"),f=d?'[rel="stylesheet"]':"";if(document.querySelector(`link[href="${u}"]${f}`))return;const g=document.createElement("link");if(g.rel=d?"stylesheet":Ge,d||(g.as="script"),g.crossOrigin="",g.href=u,p&&g.setAttribute("nonce",p),document.head.appendChild(g),d)return new Promise((h,m)=>{g.addEventListener("load",h),g.addEventListener("error",()=>m(new Error(`Unable to preload CSS for ${u}`)))})}))}function k(c){const p=new Event("vite:preloadError",{cancelable:!0});if(p.payload=c,window.dispatchEvent(p),!p.defaultPrevented)throw c}return o.then(c=>{for(const p of c||[])p.status==="rejected"&&k(p.reason);return t().catch(k)})};/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ze=e=>{for(const t in e)if(t.startsWith("aria-")||t==="role"||t==="title")return!0;return!1};/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const he=e=>e==="";/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Qe=(...e)=>e.filter((t,r,l)=>!!t&&t.trim()!==""&&l.indexOf(t)===r).join(" ").trim();/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const me=e=>e.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase();/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const We=e=>e.replace(/^([A-Z])|[\s-_]+(\w)/g,(t,r,l)=>l?l.toUpperCase():r.toLowerCase());/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Je=e=>{const t=We(e);return t.charAt(0).toUpperCase()+t.slice(1)};/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var N={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":2,"stroke-linecap":"round","stroke-linejoin":"round"};/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ye=({name:e,iconNode:t,absoluteStrokeWidth:r,"absolute-stroke-width":l,strokeWidth:o,"stroke-width":k,size:c=N.width,color:p=N.stroke,...u},{slots:d})=>Q("svg",{...N,...u,width:c,height:c,stroke:p,"stroke-width":he(r)||he(l)||r===!0||l===!0?Number(o||k||N["stroke-width"])*24/Number(c):o||k||N["stroke-width"],class:Qe("lucide",u.class,...e?[`lucide-${me(Je(e))}-icon`,`lucide-${me(e)}`]:["lucide-icon"]),...!d.default&&!Ze(u)&&{"aria-hidden":"true"}},[...t.map(f=>Q(...f)),...d.default?[d.default()]:[]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const a=(e,t)=>(r,{slots:l,attrs:o})=>Q(Ye,{...o,...r,iconNode:t,name:e},l);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Xe=a("baby",[["path",{d:"M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5",key:"1u7htd"}],["path",{d:"M15 12h.01",key:"1k8ypt"}],["path",{d:"M19.38 6.813A9 9 0 0 1 20.8 10.2a2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3c2 0 3.5 1.1 3.5 2.5s-.9 2.5-2 2.5c-.8 0-1.5-.4-1.5-1",key:"11xh7x"}],["path",{d:"M9 12h.01",key:"157uk2"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ce=a("banknote",[["rect",{width:"20",height:"12",x:"2",y:"6",rx:"2",key:"9lu3g6"}],["circle",{cx:"12",cy:"12",r:"2",key:"1c9p78"}],["path",{d:"M6 12h.01M18 12h.01",key:"113zkx"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const et=a("bike",[["circle",{cx:"18.5",cy:"17.5",r:"3.5",key:"15x4ox"}],["circle",{cx:"5.5",cy:"17.5",r:"3.5",key:"1noe27"}],["circle",{cx:"15",cy:"5",r:"1",key:"19l28e"}],["path",{d:"M12 17.5V14l-3-3 4-3 2 3h2",key:"1npguv"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const tt=a("book",[["path",{d:"M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20",key:"k3hazp"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const at=a("briefcase",[["path",{d:"M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16",key:"jecpp"}],["rect",{width:"20",height:"14",x:"2",y:"6",rx:"2",key:"i6l2r4"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const nt=a("building-2",[["path",{d:"M10 12h4",key:"a56b0p"}],["path",{d:"M10 8h4",key:"1sr2af"}],["path",{d:"M14 21v-3a2 2 0 0 0-4 0v3",key:"1rgiei"}],["path",{d:"M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2",key:"secmi2"}],["path",{d:"M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16",key:"16ra0t"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const rt=a("bus",[["path",{d:"M8 6v6",key:"18i7km"}],["path",{d:"M15 6v6",key:"1sg6z9"}],["path",{d:"M2 12h19.6",key:"de5uta"}],["path",{d:"M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3",key:"1wwztk"}],["circle",{cx:"7",cy:"18",r:"2",key:"19iecd"}],["path",{d:"M9 18h5",key:"lrx6i"}],["circle",{cx:"16",cy:"18",r:"2",key:"1v4tcr"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const st=a("calendar-clock",[["path",{d:"M16 14v2.2l1.6 1",key:"fo4ql5"}],["path",{d:"M16 2v4",key:"4m81vk"}],["path",{d:"M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5",key:"1osxxc"}],["path",{d:"M3 10h5",key:"r794hk"}],["path",{d:"M8 2v4",key:"1cmpym"}],["circle",{cx:"16",cy:"16",r:"6",key:"qoo3c4"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ot=a("calendar-days",[["path",{d:"M8 2v4",key:"1cmpym"}],["path",{d:"M16 2v4",key:"4m81vk"}],["rect",{width:"18",height:"18",x:"3",y:"4",rx:"2",key:"1hopcy"}],["path",{d:"M3 10h18",key:"8toen8"}],["path",{d:"M8 14h.01",key:"6423bh"}],["path",{d:"M12 14h.01",key:"1etili"}],["path",{d:"M16 14h.01",key:"1gbofw"}],["path",{d:"M8 18h.01",key:"lrp35t"}],["path",{d:"M12 18h.01",key:"mhygvu"}],["path",{d:"M16 18h.01",key:"kzsmim"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const lt=a("car",[["path",{d:"M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2",key:"5owen"}],["circle",{cx:"7",cy:"17",r:"2",key:"u2ysq9"}],["path",{d:"M9 17h6",key:"r8uit2"}],["circle",{cx:"17",cy:"17",r:"2",key:"axvx0g"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const it=a("cat",[["path",{d:"M12 5c.67 0 1.35.09 2 .26 1.78-2 5.03-2.84 6.42-2.26 1.4.58-.42 7-.42 7 .57 1.07 1 2.24 1 3.44C21 17.9 16.97 21 12 21s-9-3-9-7.56c0-1.25.5-2.4 1-3.44 0 0-1.89-6.42-.5-7 1.39-.58 4.72.23 6.5 2.23A9.04 9.04 0 0 1 12 5Z",key:"x6xyqk"}],["path",{d:"M8 14v.5",key:"1nzgdb"}],["path",{d:"M16 14v.5",key:"1lajdz"}],["path",{d:"M11.25 16.25h1.5L12 17l-.75-.75Z",key:"12kq1m"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ct=a("chart-column",[["path",{d:"M3 3v16a2 2 0 0 0 2 2h16",key:"c24i48"}],["path",{d:"M18 17V9",key:"2bz60n"}],["path",{d:"M13 17V5",key:"1frdt8"}],["path",{d:"M8 17v-3",key:"17ska0"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const $e=a("check",[["path",{d:"M20 6 9 17l-5-5",key:"1gmf2c"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ne=a("chevron-down",[["path",{d:"m6 9 6 6 6-6",key:"qrunsl"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const dt=a("circle-check",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"m9 12 2 2 4-4",key:"dzmm74"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ut=a("circle-dollar-sign",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8",key:"1h4pet"}],["path",{d:"M12 18V6",key:"zqpxq5"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Se=a("circle-dot",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["circle",{cx:"12",cy:"12",r:"1",key:"41hilf"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const pt=a("circle-question-mark",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3",key:"1u773s"}],["path",{d:"M12 17h.01",key:"p32p05"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ht=a("circle-x",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"m15 9-6 6",key:"1uzhvr"}],["path",{d:"m9 9 6 6",key:"z0biqf"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const mt=a("clapperboard",[["path",{d:"m12.296 3.464 3.02 3.956",key:"qash78"}],["path",{d:"M20.2 6 3 11l-.9-2.4c-.3-1.1.3-2.2 1.3-2.5l13.5-4c1.1-.3 2.2.3 2.5 1.3z",key:"1h7j8b"}],["path",{d:"M3 11h18v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z",key:"4lm6w1"}],["path",{d:"m6.18 5.276 3.1 3.899",key:"zjj9t3"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const yt=a("coffee",[["path",{d:"M10 2v2",key:"7u0qdc"}],["path",{d:"M14 2v2",key:"6buw04"}],["path",{d:"M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1",key:"pwadti"}],["path",{d:"M6 2v2",key:"colzsn"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const kt=a("dumbbell",[["path",{d:"M17.596 12.768a2 2 0 1 0 2.829-2.829l-1.768-1.767a2 2 0 0 0 2.828-2.829l-2.828-2.828a2 2 0 0 0-2.829 2.828l-1.767-1.768a2 2 0 1 0-2.829 2.829z",key:"9m4mmf"}],["path",{d:"m2.5 21.5 1.4-1.4",key:"17g3f0"}],["path",{d:"m20.1 3.9 1.4-1.4",key:"1qn309"}],["path",{d:"M5.343 21.485a2 2 0 1 0 2.829-2.828l1.767 1.768a2 2 0 1 0 2.829-2.829l-6.364-6.364a2 2 0 1 0-2.829 2.829l1.768 1.767a2 2 0 0 0-2.828 2.829z",key:"1t2c92"}],["path",{d:"m9.6 14.4 4.8-4.8",key:"6umqxw"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ee=a("file-text",[["path",{d:"M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z",key:"1oefj6"}],["path",{d:"M14 2v5a1 1 0 0 0 1 1h5",key:"wfsgrz"}],["path",{d:"M10 9H8",key:"b1mrlr"}],["path",{d:"M16 13H8",key:"t4e002"}],["path",{d:"M16 17H8",key:"z1uh3a"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ft=a("fuel",[["path",{d:"M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 0 4 0v-6.998a2 2 0 0 0-.59-1.42L18 5",key:"1wtuz0"}],["path",{d:"M14 21V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16",key:"e09ifn"}],["path",{d:"M2 21h13",key:"1x0fut"}],["path",{d:"M3 9h11",key:"1p7c0w"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const vt=a("gamepad-2",[["line",{x1:"6",x2:"10",y1:"11",y2:"11",key:"1gktln"}],["line",{x1:"8",x2:"8",y1:"9",y2:"13",key:"qnk9ow"}],["line",{x1:"15",x2:"15.01",y1:"12",y2:"12",key:"krot7o"}],["line",{x1:"18",x2:"18.01",y1:"10",y2:"10",key:"1lcuu1"}],["path",{d:"M17.32 5H6.68a4 4 0 0 0-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 0 1 9.828 16h4.344a2 2 0 0 1 1.414.586L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.545-.604-6.584-.685-7.258-.007-.05-.011-.1-.017-.151A4 4 0 0 0 17.32 5z",key:"mfqc10"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const gt=a("gauge",[["path",{d:"m12 14 4-4",key:"9kzdfg"}],["path",{d:"M3.34 19a10 10 0 1 1 17.32 0",key:"19p75a"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const xt=a("gift",[["path",{d:"M12 7v14",key:"1akyts"}],["path",{d:"M20 11v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8",key:"1sqzm4"}],["path",{d:"M7.5 7a1 1 0 0 1 0-5A4.8 8 0 0 1 12 7a4.8 8 0 0 1 4.5-5 1 1 0 0 1 0 5",key:"kc0143"}],["rect",{x:"3",y:"7",width:"18",height:"4",rx:"1",key:"1hberx"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const bt=a("graduation-cap",[["path",{d:"M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z",key:"j76jl0"}],["path",{d:"M22 10v6",key:"1lu8f3"}],["path",{d:"M6 12.5V16a6 3 0 0 0 12 0v-3.5",key:"1r8lef"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const wt=a("hammer",[["path",{d:"m15 12-9.373 9.373a1 1 0 0 1-3.001-3L12 9",key:"1hayfq"}],["path",{d:"m18 15 4-4",key:"16gjal"}],["path",{d:"m21.5 11.5-1.914-1.914A2 2 0 0 1 19 8.172v-.344a2 2 0 0 0-.586-1.414l-1.657-1.657A6 6 0 0 0 12.516 3H9l1.243 1.243A6 6 0 0 1 12 8.485V10l2 2h1.172a2 2 0 0 1 1.414.586L18.5 14.5",key:"15ts47"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Mt=a("hand-coins",[["path",{d:"M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17",key:"geh8rc"}],["path",{d:"m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9",key:"1fto5m"}],["path",{d:"m2 16 6 6",key:"1pfhp9"}],["circle",{cx:"16",cy:"9",r:"2.9",key:"1n0dlu"}],["circle",{cx:"6",cy:"5",r:"3",key:"151irh"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const _t=a("hand-heart",[["path",{d:"M11 14h2a2 2 0 0 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16",key:"1v1a37"}],["path",{d:"m14.45 13.39 5.05-4.694C20.196 8 21 6.85 21 5.75a2.75 2.75 0 0 0-4.797-1.837.276.276 0 0 1-.406 0A2.75 2.75 0 0 0 11 5.75c0 1.2.802 2.248 1.5 2.946L16 11.95",key:"fhfbnt"}],["path",{d:"m2 15 6 6",key:"10dquu"}],["path",{d:"m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a1 1 0 0 0-2.75-2.91",key:"1x6kdw"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const zt=a("heart-pulse",[["path",{d:"M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5",key:"mvr1a0"}],["path",{d:"M3.22 13H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27",key:"auskq0"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ct=a("house",[["path",{d:"M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8",key:"5wwlr5"}],["path",{d:"M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z",key:"r6nss1"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ae=a("landmark",[["path",{d:"M10 18v-7",key:"wt116b"}],["path",{d:"M11.12 2.198a2 2 0 0 1 1.76.006l7.866 3.847c.476.233.31.949-.22.949H3.474c-.53 0-.695-.716-.22-.949z",key:"1m329m"}],["path",{d:"M14 18v-7",key:"vav6t3"}],["path",{d:"M18 18v-7",key:"aexdmj"}],["path",{d:"M3 22h18",key:"8prr45"}],["path",{d:"M6 18v-7",key:"1ivflk"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const $t=a("laptop",[["path",{d:"M18 5a2 2 0 0 1 2 2v8.526a2 2 0 0 0 .212.897l1.068 2.127a1 1 0 0 1-.9 1.45H3.62a1 1 0 0 1-.9-1.45l1.068-2.127A2 2 0 0 0 4 15.526V7a2 2 0 0 1 2-2z",key:"1pdavp"}],["path",{d:"M20.054 15.987H3.946",key:"14rxg9"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const St=a("layout-dashboard",[["rect",{width:"7",height:"9",x:"3",y:"3",rx:"1",key:"10lvy0"}],["rect",{width:"7",height:"5",x:"14",y:"3",rx:"1",key:"16une8"}],["rect",{width:"7",height:"9",x:"14",y:"12",rx:"1",key:"1hutg5"}],["rect",{width:"7",height:"5",x:"3",y:"16",rx:"1",key:"ldoo1y"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Et=a("log-out",[["path",{d:"m16 17 5-5-5-5",key:"1bji2h"}],["path",{d:"M21 12H9",key:"dn1m92"}],["path",{d:"M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4",key:"1uf3rs"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const At=a("menu",[["path",{d:"M4 5h16",key:"1tepv9"}],["path",{d:"M4 12h16",key:"1lakjw"}],["path",{d:"M4 19h16",key:"1djgab"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ye=a("moon",[["path",{d:"M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401",key:"kfwtm"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Lt=a("music",[["path",{d:"M9 18V5l12-2v13",key:"1jmyc2"}],["circle",{cx:"6",cy:"18",r:"3",key:"fqmcym"}],["circle",{cx:"18",cy:"16",r:"3",key:"1hluhg"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Vt=a("package",[["path",{d:"M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z",key:"1a0edw"}],["path",{d:"M12 22V12",key:"d0xqtd"}],["polyline",{points:"3.29 7 12 12 20.71 7",key:"ousv84"}],["path",{d:"m7.5 4.27 9 5.15",key:"1c824w"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Dt=a("party-popper",[["path",{d:"M5.8 11.3 2 22l10.7-3.79",key:"gwxi1d"}],["path",{d:"M4 3h.01",key:"1vcuye"}],["path",{d:"M22 8h.01",key:"1mrtc2"}],["path",{d:"M15 2h.01",key:"1cjtqr"}],["path",{d:"M22 20h.01",key:"1mrys2"}],["path",{d:"m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10",key:"hbicv8"}],["path",{d:"m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11c-.11.7-.72 1.22-1.43 1.22H17",key:"1i94pl"}],["path",{d:"m11 2 .33.82c.34.86-.2 1.82-1.11 1.98C9.52 4.9 9 5.52 9 6.23V7",key:"1cofks"}],["path",{d:"M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2Z",key:"4kbmks"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Le=a("piggy-bank",[["path",{d:"M11 17h3v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-3a3.16 3.16 0 0 0 2-2h1a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1h-1a5 5 0 0 0-2-4V3a4 4 0 0 0-3.2 1.6l-.3.4H11a6 6 0 0 0-6 6v1a5 5 0 0 0 2 4v3a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1z",key:"1piglc"}],["path",{d:"M16 10h.01",key:"1m94wz"}],["path",{d:"M2 8v1a2 2 0 0 0 2 2h1",key:"1env43"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Pt=a("pill",[["path",{d:"m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z",key:"wa1lgi"}],["path",{d:"m8.5 8.5 7 7",key:"rvfmvr"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const It=a("plane",[["path",{d:"M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z",key:"1v9wt8"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const jt=a("plus",[["path",{d:"M5 12h14",key:"1ays0h"}],["path",{d:"M12 5v14",key:"s699le"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ve=a("receipt",[["path",{d:"M12 17V7",key:"pyj7ub"}],["path",{d:"M16 8h-6a2 2 0 0 0 0 4h4a2 2 0 0 1 0 4H8",key:"1elt7d"}],["path",{d:"M4 3a1 1 0 0 1 1-1 1.3 1.3 0 0 1 .7.2l.933.6a1.3 1.3 0 0 0 1.4 0l.934-.6a1.3 1.3 0 0 1 1.4 0l.933.6a1.3 1.3 0 0 0 1.4 0l.933-.6a1.3 1.3 0 0 1 1.4 0l.934.6a1.3 1.3 0 0 0 1.4 0l.933-.6A1.3 1.3 0 0 1 19 2a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1 1.3 1.3 0 0 1-.7-.2l-.933-.6a1.3 1.3 0 0 0-1.4 0l-.934.6a1.3 1.3 0 0 1-1.4 0l-.933-.6a1.3 1.3 0 0 0-1.4 0l-.933.6a1.3 1.3 0 0 1-1.4 0l-.934-.6a1.3 1.3 0 0 0-1.4 0l-.933.6a1.3 1.3 0 0 1-.7.2 1 1 0 0 1-1-1z",key:"ycz6yz"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const qt=a("search",[["path",{d:"m21 21-4.34-4.34",key:"14j7rj"}],["circle",{cx:"11",cy:"11",r:"8",key:"4ej97u"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ht=a("send",[["path",{d:"M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z",key:"1ffxy3"}],["path",{d:"m21.854 2.147-10.94 10.939",key:"12cjpa"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ke=a("settings",[["path",{d:"M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915",key:"1i5ecw"}],["circle",{cx:"12",cy:"12",r:"3",key:"1v7zrd"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Tt=a("shield-check",[["path",{d:"M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z",key:"oel41y"}],["path",{d:"m9 12 2 2 4-4",key:"dzmm74"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Bt=a("shirt",[["path",{d:"M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z",key:"1wgbhj"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Nt=a("shopping-bag",[["path",{d:"M16 10a4 4 0 0 1-8 0",key:"1ltviw"}],["path",{d:"M3.103 6.034h17.794",key:"awc11p"}],["path",{d:"M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z",key:"o988cm"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ot=a("shopping-cart",[["circle",{cx:"8",cy:"21",r:"1",key:"jimo8o"}],["circle",{cx:"19",cy:"21",r:"1",key:"13723u"}],["path",{d:"M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12",key:"9zh506"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ft=a("smartphone",[["rect",{width:"14",height:"20",x:"5",y:"2",rx:"2",ry:"2",key:"1yt0o3"}],["path",{d:"M12 18h.01",key:"mhygvu"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Ut=a("smile",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"M8 14s1.5 2 4 2 4-2 4-2",key:"1y1vjs"}],["line",{x1:"9",x2:"9.01",y1:"9",y2:"9",key:"yxxnd0"}],["line",{x1:"15",x2:"15.01",y1:"9",y2:"9",key:"1p4y9e"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Rt=a("sparkles",[["path",{d:"M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z",key:"1s2grr"}],["path",{d:"M20 2v4",key:"1rf3ol"}],["path",{d:"M22 4h-4",key:"gwowj6"}],["circle",{cx:"4",cy:"20",r:"2",key:"6kqj1y"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Gt=a("sprout",[["path",{d:"M14 9.536V7a4 4 0 0 1 4-4h1.5a.5.5 0 0 1 .5.5V5a4 4 0 0 1-4 4 4 4 0 0 0-4 4c0 2 1 3 1 5a5 5 0 0 1-1 3",key:"139s4v"}],["path",{d:"M4 9a5 5 0 0 1 8 4 5 5 0 0 1-8-4",key:"1dlkgp"}],["path",{d:"M5 21h14",key:"11awu3"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Kt=a("stethoscope",[["path",{d:"M11 2v2",key:"1539x4"}],["path",{d:"M5 2v2",key:"1yf1q8"}],["path",{d:"M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1",key:"rb5t3r"}],["path",{d:"M8 15a6 6 0 0 0 12 0v-3",key:"x18d4x"}],["circle",{cx:"20",cy:"10",r:"2",key:"ts1r5v"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Zt=a("store",[["path",{d:"M15 21v-5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v5",key:"slp6dd"}],["path",{d:"M17.774 10.31a1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.451 0 1.12 1.12 0 0 0-1.548 0 2.5 2.5 0 0 1-3.452 0 1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.77-3.248l2.889-4.184A2 2 0 0 1 7 2h10a2 2 0 0 1 1.653.873l2.895 4.192a2.5 2.5 0 0 1-3.774 3.244",key:"o0xfot"}],["path",{d:"M4 10.95V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8.05",key:"wn3emo"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const fe=a("sun",[["circle",{cx:"12",cy:"12",r:"4",key:"4exip2"}],["path",{d:"M12 2v2",key:"tus03m"}],["path",{d:"M12 20v2",key:"1lh1kg"}],["path",{d:"m4.93 4.93 1.41 1.41",key:"149t6j"}],["path",{d:"m17.66 17.66 1.41 1.41",key:"ptbguv"}],["path",{d:"M2 12h2",key:"1t8f8n"}],["path",{d:"M20 12h2",key:"1q8mjw"}],["path",{d:"m6.34 17.66-1.41 1.41",key:"1m8zz5"}],["path",{d:"m19.07 4.93-1.41 1.41",key:"1shlcs"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Qt=a("tags",[["path",{d:"M13.172 2a2 2 0 0 1 1.414.586l6.71 6.71a2.4 2.4 0 0 1 0 3.408l-4.592 4.592a2.4 2.4 0 0 1-3.408 0l-6.71-6.71A2 2 0 0 1 6 9.172V3a1 1 0 0 1 1-1z",key:"16rjxf"}],["path",{d:"M2 7v6.172a2 2 0 0 0 .586 1.414l6.71 6.71a2.4 2.4 0 0 0 3.191.193",key:"178nd4"}],["circle",{cx:"10.5",cy:"6.5",r:".5",fill:"currentColor",key:"12ikhr"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Wt=a("target",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["circle",{cx:"12",cy:"12",r:"6",key:"1vlfrh"}],["circle",{cx:"12",cy:"12",r:"2",key:"1c9p78"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Jt=a("tram-front",[["rect",{width:"16",height:"16",x:"4",y:"3",rx:"2",key:"1wxw4b"}],["path",{d:"M4 11h16",key:"mpoxn0"}],["path",{d:"M12 3v8",key:"1h2ygw"}],["path",{d:"m8 19-2 3",key:"13i0xs"}],["path",{d:"m18 22-2-3",key:"1p0ohu"}],["path",{d:"M8 15h.01",key:"a7atzg"}],["path",{d:"M16 15h.01",key:"rnfrdf"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Yt=a("trending-down",[["path",{d:"M16 17h6v-6",key:"t6n2it"}],["path",{d:"m22 17-8.5-8.5-5 5L2 7",key:"x473p"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const De=a("trending-up",[["path",{d:"M16 7h6v6",key:"box55l"}],["path",{d:"m22 7-8.5 8.5-5-5L2 17",key:"1t1m79"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Xt=a("triangle-alert",[["path",{d:"m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3",key:"wmoenq"}],["path",{d:"M12 9v4",key:"juzpu7"}],["path",{d:"M12 17h.01",key:"p32p05"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ea=a("trophy",[["path",{d:"M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978",key:"1n3hpd"}],["path",{d:"M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978",key:"rfe1zi"}],["path",{d:"M18 9h1.5a1 1 0 0 0 0-5H18",key:"7xy6bh"}],["path",{d:"M4 22h16",key:"57wxv0"}],["path",{d:"M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z",key:"1mhfuq"}],["path",{d:"M6 9H4.5a1 1 0 0 1 0-5H6",key:"tex48p"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ta=a("tv",[["path",{d:"m17 2-5 5-5-5",key:"16satq"}],["rect",{width:"20",height:"15",x:"2",y:"7",rx:"2",key:"1e6viu"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const aa=a("umbrella",[["path",{d:"M12 13v7a2 2 0 0 0 4 0",key:"rpgb42"}],["path",{d:"M12 2v2",key:"tus03m"}],["path",{d:"M20.992 13a1 1 0 0 0 .97-1.274 10.284 10.284 0 0 0-19.923 0A1 1 0 0 0 3 13z",key:"124nyo"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const na=a("user",[["path",{d:"M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2",key:"975kel"}],["circle",{cx:"12",cy:"7",r:"4",key:"17ys0d"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ra=a("users",[["path",{d:"M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2",key:"1yyitq"}],["path",{d:"M16 3.128a4 4 0 0 1 0 7.744",key:"16gr8j"}],["path",{d:"M22 21v-2a4 4 0 0 0-3-3.87",key:"kshegd"}],["circle",{cx:"9",cy:"7",r:"4",key:"nufk8"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const sa=a("utensils",[["path",{d:"M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2",key:"cjf0a3"}],["path",{d:"M7 2v20",key:"1473qp"}],["path",{d:"M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7",key:"j28e5"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const oa=a("wallet",[["path",{d:"M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1",key:"18etb6"}],["path",{d:"M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4",key:"xoc0q4"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const la=a("wifi",[["path",{d:"M12 20h.01",key:"zekei9"}],["path",{d:"M2 8.82a15 15 0 0 1 20 0",key:"dnpr2z"}],["path",{d:"M5 12.859a10 10 0 0 1 14 0",key:"1x1e6c"}],["path",{d:"M8.5 16.429a5 5 0 0 1 7 0",key:"1bycff"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ia=a("wrench",[["path",{d:"M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z",key:"1ngwbx"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const de=a("x",[["path",{d:"M18 6 6 18",key:"1bl5f8"}],["path",{d:"m6 6 12 12",key:"d8bk6v"}]]);/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ca=a("zap",[["path",{d:"M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z",key:"1xq2db"}]]),ve={__name:"Dropdown",props:{align:{type:String,default:"right"},width:{type:String,default:"min-w-[11rem]"},up:{type:Boolean,default:!1}},setup(e){const t=D(!1),r=D(null),l=()=>t.value=!1,o=c=>{r.value&&!r.value.contains(c.target)&&l()},k=c=>c.key==="Escape"&&l();return le(()=>{document.addEventListener("mousedown",o),document.addEventListener("keydown",k)}),X(()=>{document.removeEventListener("mousedown",o),document.removeEventListener("keydown",k)}),(c,p)=>(i(),y("div",{ref_key:"root",ref:r,class:"relative inline-block"},[T(c.$slots,"trigger",{open:t.value,toggle:()=>t.value=!t.value}),t.value?(i(),y("div",{key:0,class:C(["menu absolute",[e.align==="right"?"right-0":"left-0",e.up?"bottom-full mb-1.5":"mt-1.5",e.width]]),onClick:l},[T(c.$slots,"default",{close:l})],2)):_("",!0)],512))}},J=ie([]);let da=0;function ge(e,t="success",r=3500){const l={id:++da,message:e,type:t};J.push(l),setTimeout(()=>Pe(l.id),r)}function Pe(e){const t=J.findIndex(r=>r.id===e);t!==-1&&J.splice(t,1)}const ua={class:"pointer-events-none fixed inset-x-0 bottom-0 z-[60] flex flex-col items-center gap-2 p-4 sm:items-end sm:p-6","aria-live":"polite"},pa={class:"flex-1 text-sm text-zinc-800 dark:text-zinc-100"},ha=["onClick"],ma={__name:"Toasts",setup(e){return(t,r)=>(i(),z(be,{to:"body"},[s("div",ua,[(i(!0),y(I,null,H(n(J),l=>(i(),y("div",{key:l.id,class:"pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-pop animate-slide-in dark:border-zinc-700 dark:bg-zinc-900"},[l.type==="success"?(i(),z(n(dt),{key:0,class:"mt-0.5 h-5 w-5 shrink-0 text-emerald-500"})):(i(),z(n(ht),{key:1,class:"mt-0.5 h-5 w-5 shrink-0 text-rose-500"})),s("p",pa,b(l.message),1),s("button",{class:"text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200","aria-label":"Dismiss",onClick:o=>n(Pe)(l.id)},[x(n(de),{class:"h-4 w-4"})],8,ha)]))),128))])]))}},ya={key:0,class:"fixed inset-0 z-50 overflow-y-auto"},ka={class:"relative flex min-h-full items-end justify-center p-0 sm:items-center sm:p-6"},fa=["aria-label"],va={key:0,class:"flex items-start justify-between gap-4 px-6 pb-2 pt-5"},ga={class:"text-base font-semibold text-zinc-900 dark:text-white"},xa={key:0,class:"mt-1 text-sm text-zinc-500 dark:text-zinc-400"},ba={class:"px-6 py-4"},wa={key:1,class:"flex flex-col-reverse gap-2 rounded-b-2xl border-t border-zinc-100 bg-zinc-50/70 px-6 py-3.5 dark:border-zinc-800 dark:bg-zinc-900/60 sm:flex-row sm:items-center sm:justify-end"},Ie={__name:"Modal",props:{show:{type:Boolean,default:!1},title:{type:String,default:""},description:{type:String,default:""},size:{type:String,default:"md"}},emits:["close"],setup(e,{emit:t}){const r=e,l=t,o=D(null),k={sm:"max-w-sm",md:"max-w-lg",lg:"max-w-2xl",xl:"max-w-4xl","2xl":"max-w-6xl"},c=p=>{p.key==="Escape"&&r.show&&l("close")};return ce(()=>r.show,async p=>{var u,d;document.body.style.overflow=p?"hidden":"",p?(window.addEventListener("keydown",c),await ae(),(d=(u=o.value)==null?void 0:u.querySelector("[autofocus], input:not([type=hidden]):not([type=checkbox]), select, textarea"))==null||d.focus()):window.removeEventListener("keydown",c)},{immediate:!0}),X(()=>{window.removeEventListener("keydown",c),document.body.style.overflow=""}),(p,u)=>(i(),z(be,{to:"body"},[e.show?(i(),y("div",ya,[s("div",{class:"fixed inset-0 bg-zinc-950/40 backdrop-blur-[2px] animate-fade-in dark:bg-black/60",onClick:u[0]||(u[0]=d=>l("close"))}),s("div",ka,[s("div",{ref_key:"panel",ref:o,role:"dialog","aria-modal":"true","aria-label":e.title,class:C([[k[e.size]||k.md],"relative w-full rounded-t-2xl border border-zinc-200 bg-white shadow-pop animate-pop-in dark:border-zinc-800 dark:bg-zinc-900 sm:rounded-2xl"])},[e.title?(i(),y("div",va,[s("div",null,[s("h2",ga,b(e.title),1),e.description?(i(),y("p",xa,b(e.description),1)):_("",!0)]),s("button",{type:"button",class:"btn btn-ghost btn-icon btn-sm -mr-2 -mt-1","aria-label":"Close",onClick:u[1]||(u[1]=d=>l("close"))},[x(n(de),{class:"h-4 w-4"})])])):_("",!0),s("div",ba,[T(p.$slots,"default")]),p.$slots.footer?(i(),y("div",wa,[T(p.$slots,"footer")])):_("",!0)],10,fa)])])):_("",!0)]))}},L=ie({open:!1,title:"",message:"",confirmText:"Confirm",tone:"danger",resolve:null});function V1({title:e="Are you sure?",message:t="",confirmText:r="Delete",tone:l="danger"}={}){return new Promise(o=>{Object.assign(L,{open:!0,title:e,message:t,confirmText:r,tone:l,resolve:o})})}function te(e){var t;(t=L.resolve)==null||t.call(L,e),L.open=!1,L.resolve=null}const Ma={class:"flex gap-4 pt-2"},_a={class:"text-base font-semibold text-zinc-900 dark:text-white"},za={key:0,class:"mt-1.5 text-sm text-zinc-500 dark:text-zinc-400"},Ca={__name:"ConfirmDialog",setup(e){return(t,r)=>(i(),z(Ie,{show:n(L).open,size:"sm",onClose:r[2]||(r[2]=l=>n(te)(!1))},{footer:E(()=>[s("button",{type:"button",class:"btn btn-secondary",onClick:r[0]||(r[0]=l=>n(te)(!1))},"Cancel"),s("button",{type:"button",class:C(["btn",n(L).tone==="danger"?"btn-danger":"btn-primary"]),onClick:r[1]||(r[1]=l=>n(te)(!0))},b(n(L).confirmText),3)]),default:E(()=>[s("div",Ma,[s("div",{class:C(["flex h-10 w-10 shrink-0 items-center justify-center rounded-full",n(L).tone==="danger"?"bg-rose-100 text-rose-600 dark:bg-rose-500/15 dark:text-rose-400":"bg-brand-100 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300"])},[x(n(Xt),{class:"h-5 w-5"})],2),s("div",null,[s("h3",_a,b(n(L).title),1),n(L).message?(i(),y("p",za,b(n(L).message),1)):_("",!0)])])]),_:1},8,["show"]))}},$a=["for"],Sa={key:0,class:"ml-0.5 text-rose-500"},Ea={key:1,class:"error"},Aa={key:2,class:"hint"},O={__name:"Field",props:{label:{type:String,default:""},error:{type:String,default:""},hint:{type:String,default:""},required:{type:Boolean,default:!1},for:{type:String,default:void 0}},setup(e){return(t,r)=>(i(),y("div",null,[e.label?(i(),y("label",{key:0,for:t.$props.for,class:"label"},[q(b(e.label),1),e.required?(i(),y("span",Sa,"*")):_("",!0)],8,$a)):_("",!0),T(t.$slots,"default"),e.error?(i(),y("p",Ea,b(e.error),1)):e.hint?(i(),y("p",Aa,b(e.hint),1)):_("",!0)]))}},La={class:"relative"},Va={class:"pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-zinc-400"},Da=["id","placeholder"],Pa={__name:"MoneyInput",props:Me({error:{type:Boolean,default:!1},id:{type:String,default:void 0},placeholder:{type:String,default:"0"},prefix:{type:String,default:null}},{modelValue:{},modelModifiers:{}}),emits:["update:modelValue"],setup(e){const t=we(e,"modelValue"),r=R(),l=P(()=>r.props.currency||"৳");return(o,k)=>(i(),y("div",La,[s("span",Va,b(e.prefix??l.value),1),F(s("input",{id:e.id,"onUpdate:modelValue":k[0]||(k[0]=c=>t.value=c),type:"number",step:"0.01",min:"0",inputmode:"decimal",placeholder:e.placeholder,class:C(["input num",[{"input-error":e.error},(e.prefix??l.value).length>2?"pl-12":(e.prefix??l.value).length>1?"pl-10":"pl-8"]])},null,10,Da),[[W,t.value]])]))}},je={baby:Xe,banknote:Ce,bike:et,book:tt,briefcase:at,building:nt,bus:rt,car:lt,cat:it,"circle-dollar-sign":ut,"circle-dot":Se,"circle-help":pt,clapperboard:mt,coffee:yt,dumbbell:kt,"file-text":Ee,fuel:ft,gamepad:vt,gift:xt,"graduation-cap":bt,hammer:wt,"hand-heart":_t,"heart-pulse":zt,home:Ct,landmark:Ae,laptop:$t,music:Lt,package:Vt,"party-popper":Dt,"piggy-bank":Le,pill:Pt,plane:It,receipt:Ve,send:Ht,"shield-check":Tt,shirt:Bt,"shopping-bag":Nt,"shopping-cart":Ot,smartphone:Ft,smile:Ut,sparkles:Rt,sprout:Gt,stethoscope:Kt,store:Zt,target:Wt,train:Jt,"trending-down":Yt,"trending-up":De,trophy:ea,tv:ta,umbrella:aa,user:na,users:ra,utensils:sa,wallet:oa,wifi:la,wrench:ia,zap:ca},D1=Object.keys(je);function Ia(e){return je[e]||Se}const U={slate:["#64748b","#475569"],zinc:["#71717a","#52525b"],stone:["#78716c","#57534e"],red:["#ef4444","#dc2626"],orange:["#f97316","#ea580c"],amber:["#f59e0b","#d97706"],yellow:["#eab308","#ca8a04"],lime:["#84cc16","#65a30d"],green:["#22c55e","#16a34a"],emerald:["#10b981","#059669"],teal:["#14b8a6","#0d9488"],cyan:["#06b6d4","#0891b2"],sky:["#0ea5e9","#0284c7"],blue:["#3b82f6","#2563eb"],indigo:["#6366f1","#4f46e5"],violet:["#8b5cf6","#7c3aed"],purple:["#a855f7","#9333ea"],fuchsia:["#d946ef","#c026d3"],pink:["#ec4899","#db2777"],rose:["#f43f5e","#e11d48"]},P1=Object.keys(U);function I1(e){return(U[e]||U.zinc)[0]}function ja(e){const[t,r]=U[e]||U.zinc;return{backgroundColor:`${t}1f`,color:r}}function j1(e){return getComputedStyle(document.documentElement).getPropertyValue(e).trim()}const re={__name:"CategoryIcon",props:{icon:{type:String,default:null},color:{type:String,default:null},size:{type:String,default:"md"}},setup(e){const t=e,r=P(()=>({sm:"h-6 w-6 rounded-md",md:"h-8 w-8 rounded-lg",lg:"h-10 w-10 rounded-xl"})[t.size]),l=P(()=>({sm:"h-3.5 w-3.5",md:"h-4 w-4",lg:"h-5 w-5"})[t.size]);return(o,k)=>(i(),y("span",{class:C(["inline-flex shrink-0 items-center justify-center",r.value]),style:Be(n(ja)(e.color))},[(i(),z(_e(n(Ia)(e.icon)),{class:C(l.value),"stroke-width":"2"},null,8,["class"]))],6))}},qa={class:"truncate"},Ha={key:0,class:"menu absolute left-0 right-0 mt-1.5 min-w-[16rem] p-0"},Ta={class:"relative border-b border-zinc-100 dark:border-zinc-800"},Ba={key:0,class:"px-3 py-6 text-center text-sm text-zinc-500"},Na=["data-i"],Oa=["onMouseenter","onClick"],Fa={__name:"CategorySelect",props:Me({options:{type:Array,default:null},placeholder:{type:String,default:"Select a category"},emptyLabel:{type:String,default:""},error:{type:Boolean,default:!1},parentsOnly:{type:Boolean,default:!1},exclude:{type:[Number,null],default:null}},{modelValue:{type:[Number,String,null],default:null},modelModifiers:{}}),emits:["update:modelValue"],setup(e){const t=we(e,"modelValue"),r=e,l=R(),o=P(()=>{var $;let v=r.options??(($=l.props.lookups)==null?void 0:$.expenseCategories)??[];return r.parentsOnly&&(v=v.filter(M=>!M.parent_id)),r.exclude&&(v=v.filter(M=>M.id!==r.exclude)),v}),k=D(!1),c=D(""),p=D(0),u=D(null),d=D(null),f=D(null),g=P(()=>o.value.find(v=>String(v.id)===String(t.value))),h=P(()=>{const v=c.value.trim().toLowerCase(),$=v?o.value.filter(M=>M.label.toLowerCase().includes(v)):o.value;return r.emptyLabel&&!v?[{id:"",label:r.emptyLabel,name:r.emptyLabel,empty:!0},...$]:$});async function m(){var v;k.value=!k.value,k.value&&(c.value="",p.value=Math.max(0,h.value.findIndex($=>String($.id)===String(t.value??""))),await ae(),(v=d.value)==null||v.focus(),S())}function w(v){t.value=v.empty?"":v.id,k.value=!1}function S(){ae(()=>{var v,$;return($=(v=f.value)==null?void 0:v.querySelector(`[data-i="${p.value}"]`))==null?void 0:$.scrollIntoView({block:"nearest"})})}function G(v){v.key==="ArrowDown"?(v.preventDefault(),p.value=Math.min(h.value.length-1,p.value+1),S()):v.key==="ArrowUp"?(v.preventDefault(),p.value=Math.max(0,p.value-1),S()):v.key==="Enter"?(v.preventDefault(),h.value[p.value]&&w(h.value[p.value])):v.key==="Escape"&&(v.stopPropagation(),k.value=!1)}const B=v=>u.value&&!u.value.contains(v.target)&&(k.value=!1);return le(()=>document.addEventListener("mousedown",B)),X(()=>document.removeEventListener("mousedown",B)),(v,$)=>(i(),y("div",{ref_key:"root",ref:u,class:"relative"},[s("button",{type:"button",class:C(["input flex items-center gap-2 text-left",{"input-error":e.error}]),onClick:m,onKeydown:$[0]||($[0]=Ne(ze(M=>!k.value&&m(),["prevent"]),["down"]))},[g.value?(i(),y(I,{key:0},[x(re,{icon:g.value.icon,color:g.value.color,size:"sm",class:"-ml-1"},null,8,["icon","color"]),s("span",qa,b(g.value.label),1)],64)):(i(),y("span",{key:1,class:C(["truncate",e.emptyLabel?"text-zinc-700 dark:text-zinc-200":"text-zinc-400"])},b(e.emptyLabel||e.placeholder),3)),x(n(ne),{class:"ml-auto h-4 w-4 shrink-0 text-zinc-400"})],34),k.value?(i(),y("div",Ha,[s("div",Ta,[x(n(qt),{class:"pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400"}),F(s("input",{ref_key:"searchEl",ref:d,"onUpdate:modelValue":$[1]||($[1]=M=>c.value=M),type:"text",placeholder:"Search categories…",class:"h-10 w-full bg-transparent pl-9 pr-3 text-sm outline-none placeholder:text-zinc-400",onInput:$[2]||($[2]=M=>p.value=0),onKeydown:G},null,544),[[W,c.value]])]),s("ul",{ref_key:"listEl",ref:f,class:"max-h-64 overflow-y-auto p-1"},[h.value.length?_("",!0):(i(),y("li",Ba,"No categories match “"+b(c.value)+"”.",1)),(i(!0),y(I,null,H(h.value,(M,K)=>(i(),y("li",{key:M.id+"-"+K,"data-i":K},[s("button",{type:"button",class:C(["flex w-full items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-left text-sm",[K===p.value?"bg-zinc-100 dark:bg-zinc-800":"",M.parent_id&&!c.value?"pl-7":""]]),onMouseenter:Te=>p.value=K,onClick:Te=>w(M)},[M.empty?_("",!0):(i(),z(re,{key:0,icon:M.icon,color:M.color,size:"sm"},null,8,["icon","color"])),s("span",{class:C(["flex-1 truncate",!M.parent_id&&!M.empty?"font-medium text-zinc-900 dark:text-zinc-100":"text-zinc-700 dark:text-zinc-300"])},b(c.value?M.label:M.name),3),String(M.id)===String(t.value??"")?(i(),z(n($e),{key:1,class:"h-4 w-4 text-brand-600 dark:text-brand-400"})):_("",!0)],42,Oa)],8,Na))),128))],512)])):_("",!0)],512))}},V=ie({open:!1,type:"expense",record:null});function xe(e="expense",t=null){Object.assign(V,{open:!0,type:e,record:t})}function Z(){V.open=!1,V.record=null}const qe=()=>{try{return R().props.currency||"৳"}catch{return"৳"}},He=new Intl.NumberFormat("en-US",{maximumFractionDigits:2,minimumFractionDigits:0}),Ua=new Intl.NumberFormat("en-US",{maximumFractionDigits:0}),Ra=new Intl.NumberFormat("en-US",{notation:"compact",maximumFractionDigits:1});function q1(e,{decimals:t="auto",signed:r=!1}={}){const l=Number(e)||0,o=Math.abs(l),k=t===0?Ua.format(o):He.format(o);return`${l<0?"−":r&&l>0?"+":""}${qe()}${k}`}function H1(e){const t=Number(e)||0;return`${t<0?"−":""}${qe()}${Ra.format(Math.abs(t))}`}function T1(e){return He.format(Number(e)||0)}function B1(e,t=1){return e==null||Number.isNaN(Number(e))?"—":`${Number(e).toFixed(t)}%`}function Y(e){if(!e)return null;if(e instanceof Date)return e;const t=String(e).match(/^(\d{4})-(\d{2})-(\d{2})/);if(t)return new Date(Number(t[1]),Number(t[2])-1,Number(t[3]));const r=new Date(e);return Number.isNaN(r.getTime())?null:r}const Ga=new Intl.DateTimeFormat("en-GB",{day:"numeric",month:"short",year:"numeric"}),Ka=new Intl.DateTimeFormat("en-GB",{day:"numeric",month:"short"});function N1(e){const t=Y(e);return t?Ga.format(t):"—"}function O1(e){const t=Y(e);return t?Ka.format(t):"—"}function se(){const e=new Date;return`${e.getFullYear()}-${String(e.getMonth()+1).padStart(2,"0")}-${String(e.getDate()).padStart(2,"0")}`}function Za(e){const t=Y(e);if(!t)return null;const r=Y(se());return Math.round((t-r)/864e5)}function F1(e){const t=Za(e);return t===null?"":t===0?"Due today":t===1?"Due tomorrow":t>1?`Due in ${t} days`:t===-1?"1 day overdue":`${-t} days overdue`}function Qa(e=""){return e.split(/\s+/).filter(Boolean).slice(0,2).map(t=>t[0].toUpperCase()).join("")}const Wa={key:0,class:"seg w-full"},Ja={class:"grid gap-4 sm:grid-cols-2"},Ya={class:"grid grid-cols-2 gap-2 sm:grid-cols-4"},Xa=["onClick"],e1={class:"truncate"},t1={key:0,class:"mr-auto flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400"},a1=["disabled"],n1={__name:"TransactionDialog",setup(e){const t=R(),r=P(()=>{var u;return((u=t.props.lookups)==null?void 0:u.incomeCategories)??[]}),l=D(!1),o=Ue({date:se(),amount:"",category_id:null,income_source:"Salary",remarks:""}),k=P(()=>!!V.record),c=P(()=>V.type==="expense");ce(()=>V.open,u=>{var f,g;if(!u)return;o.clearErrors();const d=V.record;d?o.defaults({date:d.date,amount:d.amount,category_id:d.category_id??null,income_source:d.income_source??((f=r.value[0])==null?void 0:f.name)??"Salary",remarks:d.remarks??""}):o.defaults({date:o.date||se(),amount:"",category_id:l.value?o.category_id:null,income_source:o.income_source||((g=r.value[0])==null?void 0:g.name)||"Salary",remarks:""}),o.reset()});function p(){const u=c.value?"/admin/expenses":"/admin/incomes",d=g=>c.value?{date:g.date,amount:g.amount,category_id:g.category_id,remarks:g.remarks}:{date:g.date,amount:g.amount,income_source:g.income_source,remarks:g.remarks},f={preserveScroll:!0,onSuccess:()=>{!k.value&&l.value?(o.amount="",o.remarks="",o.clearErrors()):Z()}};k.value?o.transform(d).put(`${u}/${V.record.id}`,f):o.transform(d).post(u,f)}return(u,d)=>(i(),z(Ie,{show:n(V).open,title:k.value?c.value?"Edit expense":"Edit income":"Add transaction",onClose:n(Z)},{footer:E(()=>[k.value?_("",!0):(i(),y("label",t1,[F(s("input",{"onUpdate:modelValue":d[6]||(d[6]=f=>l.value=f),type:"checkbox",class:"h-4 w-4 rounded"},null,512),[[Oe,l.value]]),d[8]||(d[8]=q(" Add another after saving ",-1))])),s("button",{type:"button",class:"btn btn-secondary",onClick:d[7]||(d[7]=(...f)=>n(Z)&&n(Z)(...f))},"Cancel"),s("button",{type:"submit",form:"txn-form",class:"btn btn-primary",disabled:n(o).processing},b(k.value?"Save changes":c.value?"Add expense":"Add income"),9,a1)]),default:E(()=>[s("form",{id:"txn-form",class:"space-y-4",onSubmit:ze(p,["prevent"])},[k.value?_("",!0):(i(),y("div",Wa,[s("button",{type:"button",class:C(["flex-1",{on:c.value}]),onClick:d[0]||(d[0]=f=>n(V).type="expense")},"Expense",2),s("button",{type:"button",class:C(["flex-1",{on:!c.value}]),onClick:d[1]||(d[1]=f=>n(V).type="income")},"Income",2)])),s("div",Ja,[x(O,{label:"Amount",error:n(o).errors.amount,required:"",for:"txn-amount"},{default:E(()=>[x(Pa,{id:"txn-amount",modelValue:n(o).amount,"onUpdate:modelValue":d[2]||(d[2]=f=>n(o).amount=f),error:!!n(o).errors.amount,autofocus:""},null,8,["modelValue","error"])]),_:1},8,["error"]),x(O,{label:"Date",error:n(o).errors.date,required:"",for:"txn-date"},{default:E(()=>[F(s("input",{id:"txn-date","onUpdate:modelValue":d[3]||(d[3]=f=>n(o).date=f),type:"date",class:C(["input",{"input-error":n(o).errors.date}])},null,2),[[W,n(o).date]])]),_:1},8,["error"])]),c.value?(i(),z(O,{key:1,label:"Category",error:n(o).errors.category_id,required:""},{default:E(()=>[x(Fa,{modelValue:n(o).category_id,"onUpdate:modelValue":d[4]||(d[4]=f=>n(o).category_id=f),error:!!n(o).errors.category_id},null,8,["modelValue","error"])]),_:1},8,["error"])):(i(),z(O,{key:2,label:"Source",error:n(o).errors.income_source,required:""},{default:E(()=>[s("div",Ya,[(i(!0),y(I,null,H(r.value,f=>(i(),y("button",{key:f.id,type:"button",class:C(["flex items-center gap-2 rounded-lg border px-2.5 py-2 text-left text-[13px] font-medium transition",n(o).income_source===f.name?"border-brand-500 bg-brand-50 text-brand-700 ring-1 ring-brand-500 dark:bg-brand-500/10 dark:text-brand-200":"border-zinc-200 text-zinc-600 hover:border-zinc-300 dark:border-zinc-700 dark:text-zinc-300"]),onClick:g=>n(o).income_source=f.name},[x(re,{icon:f.icon,color:f.color,size:"sm"},null,8,["icon","color"]),s("span",e1,b(f.name),1)],10,Xa))),128))])]),_:1},8,["error"])),x(O,{label:"Note",error:n(o).errors.remarks,for:"txn-remarks"},{default:E(()=>[F(s("input",{id:"txn-remarks","onUpdate:modelValue":d[5]||(d[5]=f=>n(o).remarks=f),type:"text",maxlength:"255",class:"input",placeholder:"What was it for?"},null,512),[[W,n(o).remarks]])]),_:1},8,["error"])],32)]),_:1},8,["show","title","onClose"]))}},j=D(document.documentElement.classList.contains("dark")),oe=new Set;function r1(){return{isDark:j,toggle:()=>{j.value=!j.value,document.documentElement.classList.toggle("dark",j.value);try{localStorage.setItem("theme",j.value?"dark":"light")}catch{}oe.forEach(t=>t(j.value))}}}function U1(e){return oe.add(e),()=>oe.delete(e)}const s1={class:"min-h-screen"},o1={class:"flex h-16 shrink-0 items-center gap-3 px-5"},l1={class:"flex-1 overflow-y-auto px-3 pb-6 pt-2"},i1={class:"mb-1.5 px-3 text-[11px] font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500"},c1={class:"truncate"},d1={key:0,class:"num ml-auto rounded-full bg-amber-100 px-1.5 text-[11px] font-semibold text-amber-800 dark:bg-amber-500/20 dark:text-amber-300"},u1={class:"border-t border-zinc-100 p-3 dark:border-zinc-800"},p1=["onClick"],h1={class:"flex h-8 w-8 items-center justify-center rounded-full bg-zinc-900 text-xs font-semibold text-white dark:bg-zinc-100 dark:text-zinc-900"},m1={class:"min-w-0 flex-1"},y1={class:"block truncate text-sm font-medium text-zinc-900 dark:text-zinc-100"},k1={class:"block truncate text-xs text-zinc-500"},f1={class:"lg:pl-64"},v1={class:"sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-zinc-200/80 bg-white/80 px-4 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/80 sm:px-6 lg:px-8"},g1=["onClick"],x1={class:"num"},b1={class:"max-h-72 overflow-y-auto"},w1=["onClick"],M1={class:"flex-1"},_1={key:0,class:"text-[11px] text-zinc-400"},z1={class:"ml-auto flex items-center gap-1.5"},C1=["aria-label"],$1={class:"mx-auto max-w-[1400px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8"},S1={__name:"AdminLayout",setup(e){const t=R(),{isDark:r,toggle:l}=r1(),o=D(!1),k=P(()=>{var h;return[{group:"Overview",items:[{label:"Dashboard",href:"/admin",icon:St,exact:!0},{label:"Reports",href:"/admin/reports",icon:ct}]},{group:"Transactions",items:[{label:"Expenses",href:"/admin/expenses",icon:Ve},{label:"Income",href:"/admin/incomes",icon:Ce}]},{group:"Planning",items:[{label:"Budgets",href:"/admin/budgets",icon:gt},{label:"Bills & Subscriptions",href:"/admin/bills",icon:st,badge:(h=t.props.badges)==null?void 0:h.bills},{label:"Savings Goals",href:"/admin/goals",icon:Le}]},{group:"Assets & Loans",items:[{label:"Investments",href:"/admin/investments",icon:De},{label:"Money Lent",href:"/admin/lending",icon:Mt},{label:"Money Borrowed",href:"/admin/borrowing",icon:Ae}]},{group:"Business",items:[{label:"Invoices",href:"/admin/invoices",icon:Ee}]},{group:"Setup",items:[{label:"Categories",href:"/admin/categories",icon:Qt},{label:"Settings",href:"/admin/settings",icon:ke}]}]}),c=P(()=>t.url.split("?")[0]),p=h=>h.exact?c.value===h.href||c.value===h.href+"/":c.value.startsWith(h.href),u=P(()=>t.props.calendar);function d(h){ue.post("/admin/year",{year:h},{preserveScroll:!0})}ce(()=>t.props.flash,h=>{h!=null&&h.success&&ge(h.success,"success"),h!=null&&h.error&&ge(h.error,"error",6e3)},{immediate:!0,deep:!0});const f=ue.on("navigate",()=>o.value=!1);function g(h){const m=(h.target.tagName||"").toLowerCase();h.metaKey||h.ctrlKey||h.altKey||["input","textarea","select"].includes(m)||h.target.isContentEditable||h.key==="n"&&!V.open&&(h.preventDefault(),xe("expense"))}return le(()=>window.addEventListener("keydown",g)),X(()=>{window.removeEventListener("keydown",g),f()}),(h,m)=>(i(),y("div",s1,[o.value?(i(),y("div",{key:0,class:"fixed inset-0 z-40 bg-zinc-950/40 backdrop-blur-[2px] lg:hidden",onClick:m[0]||(m[0]=w=>o.value=!1)})):_("",!0),s("aside",{class:C(["fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-zinc-200/80 bg-white transition-transform duration-200 dark:border-zinc-800 dark:bg-[#0c0c0e] lg:translate-x-0",o.value?"translate-x-0 shadow-pop":"-translate-x-full"])},[s("div",o1,[m[6]||(m[6]=s("div",{class:"flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-lg font-semibold text-white shadow-sm"},"৳",-1)),m[7]||(m[7]=s("div",{class:"leading-tight"},[s("p",{class:"text-[15px] font-semibold tracking-tight text-zinc-900 dark:text-white"},"Finance"),s("p",{class:"text-xs text-zinc-500"},"Personal money manager")],-1)),s("button",{class:"btn btn-ghost btn-icon btn-sm ml-auto lg:hidden","aria-label":"Close menu",onClick:m[1]||(m[1]=w=>o.value=!1)},[x(n(de),{class:"h-4 w-4"})])]),s("nav",l1,[(i(!0),y(I,null,H(k.value,w=>(i(),y("div",{key:w.group,class:"mb-5"},[s("p",i1,b(w.group),1),(i(!0),y(I,null,H(w.items,S=>(i(),z(n(ee),{key:S.href,href:S.href,class:C(["group relative mb-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors",p(S)?"bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-200":"text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/70 dark:hover:text-zinc-100"])},{default:E(()=>[(i(),z(_e(S.icon),{class:C(["h-[18px] w-[18px] shrink-0",p(S)?"text-brand-600 dark:text-brand-300":"text-zinc-400 group-hover:text-zinc-600 dark:text-zinc-500 dark:group-hover:text-zinc-300"])},null,8,["class"])),s("span",c1,b(S.label),1),S.badge?(i(),y("span",d1,b(S.badge),1)):_("",!0)]),_:2},1032,["href","class"]))),128))]))),128))]),s("div",u1,[x(ve,{align:"left",width:"w-56",up:""},{trigger:E(({toggle:w})=>{var S,G,B;return[s("button",{class:"flex w-56 items-center gap-3 rounded-lg p-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-800/70",onClick:w},[s("span",h1,b(n(Qa)((S=n(t).props.auth.user)==null?void 0:S.name)),1),s("span",m1,[s("span",y1,b((G=n(t).props.auth.user)==null?void 0:G.name),1),s("span",k1,b((B=n(t).props.auth.user)==null?void 0:B.email),1)]),x(n(ne),{class:"h-4 w-4 text-zinc-400"})],8,p1)]}),default:E(()=>[s("div",null,[x(n(ee),{href:"/admin/settings",class:"menu-item"},{default:E(()=>[x(n(ke),{class:"h-4 w-4"}),m[8]||(m[8]=q(" Settings",-1))]),_:1}),s("button",{class:"menu-item",onClick:m[2]||(m[2]=(...w)=>n(l)&&n(l)(...w))},[n(r)?(i(),z(n(fe),{key:0,class:"h-4 w-4"})):(i(),z(n(ye),{key:1,class:"h-4 w-4"})),q(" "+b(n(r)?"Light mode":"Dark mode"),1)]),x(n(ee),{href:"/admin/logout",method:"post",as:"button",class:"menu-item text-rose-600 dark:text-rose-400"},{default:E(()=>[x(n(Et),{class:"h-4 w-4"}),m[9]||(m[9]=q(" Sign out",-1))]),_:1})])]),_:1})])],2),s("div",f1,[s("header",v1,[s("button",{class:"btn btn-ghost btn-icon -ml-2 lg:hidden","aria-label":"Open menu",onClick:m[3]||(m[3]=w=>o.value=!0)},[x(n(At),{class:"h-5 w-5"})]),u.value?(i(),z(ve,{key:0,align:"left",width:"w-44"},{trigger:E(({toggle:w})=>[s("button",{class:"btn btn-secondary btn-sm gap-2",onClick:w,title:"Year shown in reports and lists (January–December)"},[x(n(ot),{class:"h-4 w-4 text-zinc-400"}),s("span",x1,b(u.value.selected),1),m[10]||(m[10]=s("span",{class:"hidden text-xs font-normal text-zinc-400 sm:inline"},"Jan – Dec",-1)),x(n(ne),{class:"h-3.5 w-3.5 text-zinc-400"})],8,g1)]),default:E(()=>[m[11]||(m[11]=s("p",{class:"px-2.5 pb-1 pt-1.5 text-[11px] font-semibold uppercase tracking-wider text-zinc-400"},"Calendar year",-1)),s("div",b1,[(i(!0),y(I,null,H(u.value.available,w=>(i(),y("button",{key:w,class:"menu-item num",onClick:S=>d(w)},[s("span",M1,b(w),1),w===u.value.current?(i(),y("span",_1,"current")):_("",!0),w===u.value.selected?(i(),z(n($e),{key:1,class:"h-4 w-4 text-brand-600"})):_("",!0)],8,w1))),128))])]),_:1})):_("",!0),s("div",z1,[s("button",{class:"btn btn-ghost btn-icon","aria-label":n(r)?"Switch to light mode":"Switch to dark mode",onClick:m[4]||(m[4]=(...w)=>n(l)&&n(l)(...w))},[n(r)?(i(),z(n(fe),{key:0,class:"h-[18px] w-[18px]"})):(i(),z(n(ye),{key:1,class:"h-[18px] w-[18px]"}))],8,C1),s("button",{class:"btn btn-primary btn-sm sm:h-9 sm:px-3.5",title:"Add transaction (N)",onClick:m[5]||(m[5]=w=>n(xe)("expense"))},[x(n(jt),{class:"h-4 w-4"}),m[12]||(m[12]=q()),m[13]||(m[13]=s("span",{class:"hidden sm:inline"},"Add transaction",-1)),m[14]||(m[14]=s("kbd",{class:"ml-1 hidden rounded border border-white/25 px-1 text-[10px] font-medium text-white/80 md:inline"},"N",-1))])])]),s("main",$1,[T(h.$slots,"default")])]),x(n1),x(Ca),x(ma)]))}},E1=Object.assign({"./Pages/Auth/Login.vue":()=>A(()=>import("./Login-DXAJ89uc.js"),__vite__mapDeps([0,1,2])),"./Pages/Bills/Index.vue":()=>A(()=>import("./Index-D0lVKLhi.js"),__vite__mapDeps([3,1,2,4,5,6,7,8,9])),"./Pages/Borrowing/Index.vue":()=>A(()=>import("./Index-Dni9Z1bG.js"),__vite__mapDeps([10,11,1,2,4,12,13,14,15,16,17,18,8,6,5,19,7])),"./Pages/Budgets/Index.vue":()=>A(()=>import("./Index-Dpz7nxKo.js"),__vite__mapDeps([20,1,2,4,19,5,6,21,17,18,7,8])),"./Pages/Categories/Index.vue":()=>A(()=>import("./Index-CpYJdiUk.js"),__vite__mapDeps([22,1,2,4,13,6,5,23,18,7,8,9])),"./Pages/Dashboard.vue":()=>A(()=>import("./Dashboard-C_FGoJxY.js"),__vite__mapDeps([24,2,1,12,21,25,19,6])),"./Pages/Expenses/Index.vue":()=>A(()=>import("./Index-DZEAz6N4.js"),__vite__mapDeps([26,1,2,4,13,27,15,16,17,18,8,6,21,25,28,7])),"./Pages/Goals/Index.vue":()=>A(()=>import("./Index-B-Ip2V1J.js"),__vite__mapDeps([29,1,2,4,23,6,7,8,9])),"./Pages/Incomes/Index.vue":()=>A(()=>import("./Index-BnT7QfBs.js"),__vite__mapDeps([30,1,2,4,13,27,15,16,17,18,8,6,21,25,28,7])),"./Pages/Investments/Index.vue":()=>A(()=>import("./Index-COQz6pep.js"),__vite__mapDeps([31,1,2,4,12,13,14,15,16,17,18,8,6,5,19,7])),"./Pages/Invoices/Form.vue":()=>A(()=>import("./Form-BZG6OrxU.js"),__vite__mapDeps([32,1,2,33,28,8,16])),"./Pages/Invoices/Index.vue":()=>A(()=>import("./Index-D-Fq7-Hw.js"),__vite__mapDeps([34,1,2,4,13,27,15,16,17,18,8,6,33,28,7,9])),"./Pages/Lending/Index.vue":()=>A(()=>import("./Index-i3ohnERy.js"),__vite__mapDeps([35,11,1,2,4,12,13,14,15,16,17,18,8,6,5,19,7])),"./Pages/Reports/Index.vue":()=>A(()=>import("./Index-DF9wI4je.js"),__vite__mapDeps([36,2,1,4,12,21,6,19,18,28])),"./Pages/Settings/Index.vue":()=>A(()=>import("./Index-BloN52_X.js"),__vite__mapDeps([37,1,2,4]))});Re({title:e=>e?`${e} · Finance`:"Finance",resolve:async e=>{const t=E1[`./Pages/${e}.vue`];if(!t)throw new Error(`Admin page not found: ${e}`);const r=await t();return r.default.layout===void 0&&(r.default.layout=S1),r},setup({el:e,App:t,props:r,plugin:l}){Fe({render:()=>Q(t,r)}).use(l).mount(e)},progress:{color:"#6366f1",delay:150}});export{qt as A,H1 as B,dt as C,U1 as D,j1 as E,Ee as F,gt as G,Mt as H,D1 as I,ot as J,Ae as L,jt as P,Rt as S,Xt as T,oa as W,de as X,re as _,ve as a,st as b,a as c,N1 as d,O as e,Pa as f,Fa as g,Ie as h,V1 as i,Za as j,De as k,I1 as l,q1 as m,T1 as n,xe as o,B1 as p,ne as q,F1 as r,O1 as s,se as t,ea as u,Le as v,P1 as w,ja as x,Ia as y,Qa as z};
