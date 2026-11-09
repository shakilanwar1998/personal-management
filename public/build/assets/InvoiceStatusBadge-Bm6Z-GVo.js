import{c as d}from"./app-Cor-csDE.js";import{b as t,c as s,i as n,t as o,n as l,d as u,y as i}from"./runtime-dom.esm-bundler-eRQWhJR1.js";/**
 * @license lucide-vue-next v1.0.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const v=d("eye",[["path",{d:"M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0",key:"1nclc0"}],["circle",{cx:"12",cy:"12",r:"3",key:"1v7zrd"}]]),g={class:"inline-flex items-center gap-1"},p={key:0,class:"badge badge-red",title:"Due date has passed"},f={__name:"InvoiceStatusBadge",props:{status:{type:String,required:!0},overdue:{type:Boolean,default:!1}},setup(e){const a=e,c={draft:"badge-gray",sent:"badge-blue",due:"badge-amber",paid:"badge-green",overdue:"badge-red",cancelled:"badge-gray line-through decoration-zinc-400/60"},r=i(()=>a.status.charAt(0).toUpperCase()+a.status.slice(1));return(b,m)=>(t(),s("span",g,[n("span",{class:l(["badge",c[e.status]||"badge-gray"])},o(r.value),3),e.overdue&&e.status!=="overdue"?(t(),s("span",p,"Overdue")):u("",!0)]))}};export{v as E,f as _};
