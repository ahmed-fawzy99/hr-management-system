import{a as d,b as t}from"./GoBackNavLink-7e67720f.js";import{a as o,b as c,d as a,e as r,h as n,t as s,i,F as u}from"./app-a72bd68e.js";const f={key:0,class:"space-x-8 rtl:space-x-reverse sm:flex"},_={__name:"AttendanceTabs",setup(h){return(e,l)=>(o(),c(u,null,[a(d),a(t,{href:e.route("attendance.dashboard"),active:e.route().current("attendance.dashboard")},{default:r(()=>[n(s(e.__("Dashboard")),1)]),_:1},8,["href","active"]),e.$page.props.auth.user.roles.includes("admin")?(o(),c("div",f,[a(t,{href:e.route("attendances.index"),active:e.route().current("attendances.index")||e.route().current("attendance.show")},{default:r(()=>[n(s(e.__("Attendance List")),1)]),_:1},8,["href","active"]),a(t,{href:e.route("attendances.create"),active:e.route().current("attendances.create")},{default:r(()=>[n(s(e.__("Take/Edit Attendance")),1)]),_:1},8,["href","active"])])):i("",!0)],64))}};export{_};
