import Profile from "@/pages/profile/Profile";
//Profile sub pages
import ProfileSets from "@/pages/profile/sub/Sets";
import ProfileCollections from "@/pages/profile/sub/Collections";
import ProfileItems from "@/pages/profile/sub/Items";
import ProfileLikes from "@/pages/profile/sub/Likes";
import ProfileWins from "@/pages/profile/sub/Wins";
import ProfileFollowing from "@/pages/profile/sub/Following";
import ProfileFollowers from "@/pages/profile/sub/Followers";
import ProfileBlocked from "@/pages/profile/sub/Blocked";
import ProfileEdit from "@/pages/profile/Edit";
export default [
  {
    path: "/profile/edit",
    component: ProfileEdit,
    meta: { requiresAuth: true }
  },
  {
    path: "/profile/:userId(\\d+|me)",
    alias: "/user/:userId(\\d+|me)",
    component: Profile,
    meta: { requiresAuth: true },
    children: [
      {
        path: "blocked",
        component: ProfileBlocked
      },
      {
        path: "collections",
        component: ProfileCollections
      },
      {
        path: "followers",
        component: ProfileFollowers
      },
      {
        path: "following",
        component: ProfileFollowing
      },
      {
        path: "following",
        component: ProfileFollowing
      },
      {
        path: "likes",
        component: ProfileLikes
      },
      {
        path: "sets",
        component: ProfileSets
      },
      {
        path: "wins",
        component: ProfileWins
      },
      { path: "/", redirect: "sets" }
    ]
  }
];
