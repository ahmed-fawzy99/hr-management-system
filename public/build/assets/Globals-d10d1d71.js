import{_ as c}from"./GoBackNavLink-7e67720f.js";import{G as b,H as g,a as h,b as y,d as l,u as D,Z as p,e as a,f as d,t,h as s,F as z}from"./app-a72bd68e.js";import{_ as O}from"./FlexButton-1afc2f15.js";import{_ as v}from"./OrgTabs-38788d61.js";import{_ as w}from"./Card-df9bb34b.js";import{M as N}from"./ModifyIcon-0f3fd9ae.js";import{D as j,_ as o,a as u,b as i}from"./DD-a0a32140.js";import"./_plugin-vue_export-helper-c27b6911.js";function k(n){const e=window.location.search.substring(1).split("&");for(let _=0;_<e.length;_++){const[f,r]=e[_].split("=");if(f===n)return r}return!1}const B={class:"py-8"},$={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},I={class:"flex justify-between items-center mb-4"},M={class:"card-header !mb-4"},P={class:"flex gap-4"},S={class:"card-subheader"},J={__name:"Globals",props:{globals:Object,href:String},setup(n){const m=n;return b(()=>{k("reload")==="1"&&g.tz.setDefault(m.globals.timezone)}),(e,_)=>(h(),y(z,null,[l(D(p),{title:e.__("Organization Data")},null,8,["title"]),l(c,null,{tabs:a(()=>[l(v)]),default:a(()=>[d("div",B,[d("div",$,[l(w,{class:"!mt-0"},{default:a(()=>[d("div",null,[d("div",I,[d("h1",M,t(e.__("Organization Details")),1),d("div",P,[l(O,{text:e.__("Modify Organization Data"),href:e.route("globals.edit")},{default:a(()=>[l(N)]),_:1},8,["text","href"])])]),d("h2",S,t(e.__("Basic Info")),1),l(j,null,{default:a(()=>[l(o,{colored:""},{default:a(()=>[l(u,null,{default:a(()=>[s(t(e.__("Organization Name")),1)]),_:1}),l(i,null,{default:a(()=>[s(t(n.globals.organization_name),1)]),_:1})]),_:1}),l(o,{colored:""},{default:a(()=>[l(u,null,{default:a(()=>[s(t(e.__("Timezone")),1)]),_:1}),l(i,null,{default:a(()=>[s(t(n.globals.timezone),1)]),_:1})]),_:1}),l(o,null,{default:a(()=>[l(u,null,{default:a(()=>[s(t(e.__("Organization IP Address")),1)]),_:1}),l(i,null,{default:a(()=>[s(t((n.globals.is_ip_based?e.__("[:status]",{status:e.__("Enabled")})+" ":e.__("[:status]",{status:e.__("Disabled")})+" ")+(n.globals.ip!=null?JSON.parse(n.globals.ip).join(", "):"")),1)]),_:1})]),_:1}),l(o,null,{default:a(()=>[l(u,null,{default:a(()=>[s(t(e.__("Email")),1)]),_:1}),l(i,null,{default:a(()=>[s(t(n.globals.email),1)]),_:1})]),_:1}),l(o,{colored:""},{default:a(()=>[l(u,null,{default:a(()=>[s(t(e.__("Address")),1)]),_:1}),l(i,null,{default:a(()=>[s(t(n.globals.organization_address),1)]),_:1})]),_:1}),l(o,{colored:""},{default:a(()=>[l(u,null,{default:a(()=>[s(t(e.__("Absence Limit (Per Year)")),1)]),_:1}),l(i,null,{default:a(()=>[s(t(n.globals.absence_limit+" "+e.__("Days")),1)]),_:1})]),_:1}),l(o,null,{default:a(()=>[l(u,null,{default:a(()=>[s(t(e.__("Weekend Off Days")),1)]),_:1}),l(i,null,{default:a(()=>[s(t(JSON.parse(n.globals.weekend_off_days).map(f=>f.trim().replace(/^\w/,r=>r.toUpperCase())).join(", ")),1)]),_:1})]),_:1}),l(o,null,{default:a(()=>[l(u,null,{default:a(()=>[s(t(e.__("Payroll Day")),1)]),_:1}),l(i,null,{default:a(()=>[s(t(n.globals.payroll_day+" "+e.__("of the month")),1)]),_:1})]),_:1}),l(o,{colored:""},{default:a(()=>[l(u,null,{default:a(()=>[s(t(e.__("Income Tax Percentage")),1)]),_:1}),l(i,null,{default:a(()=>[s(t(n.globals.income_tax+"%"),1)]),_:1})]),_:1}),l(o,{colored:""},{default:a(()=>[l(u),l(i)]),_:1})]),_:1})])]),_:1})])])]),_:1})],64))}};export{J as default};
