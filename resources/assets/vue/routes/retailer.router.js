import Retailer from '@/pages/retailer/Retailer';
import ImportItems from "@/pages/retailer/sub/ImportItems";
import OrganizeItems from "@/pages/retailer/sub/OrganizeItems";
import NewItem from "@/pages/retailer/sub/NewItem";
import EditItem from "@/pages/retailer/sub/EditItem";
import RetailerHelp from "@/pages/retailer/sub/RetailerHelp";
export default {
  path: "/retailer",
  component: Retailer,
  children: [
    { path: "/", redirect: "allitems/1" },    
    { path: "allitems", redirect: "allitems/1" },    
    {
      path:"allitems/:allItemsOffset(\\d+)",
      component: OrganizeItems
    },
    {
      path:"new",
      component: NewItem
    },
    {
      path:"edit/:myItemId(\\d+)",
      component: EditItem
    },
    {
      path:"import",
      component: ImportItems
    },
    {
      path:"help",
      component: RetailerHelp
    }
  ]
};