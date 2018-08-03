import axios from "axios";
window.baseURL = "https://modasti.uniative.com";
const axiosI = axios.create({
  baseURL: window.baseURL + "/api"
});

axiosI.interceptors.request.use(
  function(request) {
    if (typeof window === "undefined") {
      return request;
    }

    request.headers["Authorization"] =
      "Bearer " +
      (window._store.getters.api_token ||
        localStorage.getItem("api_token") ||
        "");

    return request;
  },
  function(error) {
    return Promise.reject(error);
  }
);

axiosI.interceptors.response.use(
  function(response) {
    if (typeof window === "undefined") {
      return response;
    }
    return response;
  },
  function(error) {
    if (error.response.status == 401) {
      localStorage.removeItem("api_token");
      localStorage.removeItem("user");
      if (window.location != "/#/?popup=login");
      window.location = "/#/?popup=login";
    } else if (error.response.status == 500) {
      window.location = "/#/500";
    }
    return Promise.reject(error);
  }
);

export default axiosI;
