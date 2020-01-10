function callbackFn(details) {
  return {
    authCredentials: {
      username: "%username",
      password: "%password"
    }
  };
}

chrome.webRequest.onAuthRequired.addListener(
  callbackFn,
  { urls: ["<all_urls>"] },
  ['blocking']
);


var config = {
  mode: "fixed_servers",
  rules: {
    singleProxy: {
      scheme: "http",
      host: "%proxy_host",
      port: parseInt("%proxy_port")
    },
    /*bypassList: ["wordstat.yandex.com", "2ip.com"]*/
  }
};

chrome.proxy.settings.set({ value: config, scope: "regular" }, function () { });
