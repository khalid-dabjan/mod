import Contest from '@/pages/contest/Cotest';
import ContestTab from "@/pages/contest/sub/ContestTab";
import Details from "@/pages/contest/sub/Details";
export default {
  path: "/contest",
  component: Contest,
  children: [
    { path: "/", redirect: "s/new" },    
    { path :"s" , redirect:"s/new"},
    {
      path:"s/:contestTab(new|old)",
      component: ContestTab
    },
    {
      path:":contId(\\d+)",
      component: Details
    }
  ]
};