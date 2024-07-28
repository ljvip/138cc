<script setup>
import { ref, reactive } from "vue";
// 开奖数据
const headData = reactive({
  xamlh: [],
  bjamlh: [],
  lhc: [],
  twlh: [],
  lht: [],
  zac: [],
});
// usdt价格
const getRate = ref([]);

// 获取星期几
const getWeekName = (date) => {
  let week = new Date(date).getDay();
  let weekText = "";
  if (week == 0) {
    weekText = "星期天";
  } else if (week == 1) {
    weekText = "星期一";
  } else if (week == 2) {
    weekText = "星期二";
  } else if (week == 3) {
    weekText = "星期三";
  } else if (week == 4) {
    weekText = "星期四";
  } else if (week == 5) {
    weekText = "星期五";
  } else if (week == 6) {
    weekText = "星期六";
  }

  return weekText;
};

// 获取开奖数据
function getHeadData(codes = "") {
  headData[codes] = [];
  post(
    `https://apis.168kj.vip/apiw/issuex?codes=${codes}&pt=138nav&uid=61258&mkey=96396ae0d51ef0740236fb7256e4e8f7&t=1701595404997`,
    {},
    {}
  ).then((res) => {
    headData[res.data[0].code] = res.data[0];
  });
}

// 获取当前虚拟币价格
function getTheCurrentVirtualCurrencyPrice() {
  post(
    "https://api.1388cd.com/hapi/getRate?type=0",
    {
      type: 0,
      uid: 1046,
    },
    {
      Authorization: "Bearer 96396ae0d51ef0740236fb7256e4e8f7",
    }
  ).then((res) => {
    getRate.value = res.data;
  });
}

// post 请求
async function post(url = "", data = {}, headers = {}) {
  const response = await fetch(url, {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json",
      ...headers,
    },
    redirect: "follow",
    referrerPolicy: "no-referrer",
    body: JSON.stringify(data),
  });
  return response.json();
}

getTheCurrentVirtualCurrencyPrice();
getHeadData("xamlh");
getHeadData("bjamlh");
getHeadData("lhc");
getHeadData("twlh");
getHeadData("lht");

const insertStr = (source, start, newStr) => {
  return source.slice(0, start) + newStr + source.slice(start);
};

const getZacData = () => {
  headData["zac"] = [];
  fetch("https://php.138cc.us/", {
    method: "POST",
    body: JSON.stringify({}),
  })
    .then((response) => {
      return response.json();
    })
    .then((res) => {
      let data = res.list;
      let ballsHtml = ``;
      let sxiao = data.sxiao.split(",");
      let openCode = data.openCode.split(",");
      let bose = data.bose.split(",");
      let binfoSum = 0;

      const date = new Date(data.nexttime);
      const year = date.getFullYear().toString().padStart(4, "0");
      const month = (date.getMonth() + 1).toString().padStart(2, "0");
      const day = date.getDate().toString().padStart(2, "0");
      const hour = date.getHours().toString().padStart(2, "0");
      const minute = date.getMinutes().toString().padStart(2, "0");
      const second = date.getSeconds().toString().padStart(2, "0");
      let nextTime = `${year}-${month}-${day} ${hour}:${minute}:${second}`;

      sxiao.forEach((v, i) => {
        binfoSum = Number(binfoSum) + Number(openCode[i]);
        ballsHtml += `<li>`;
        ballsHtml += `<p class="${bose[i]}ball b${openCode[i]}">${openCode[i]}</p>`;
        ballsHtml += `<span>${v}</span>`;
        ballsHtml += `</li>`;
      });

      headData.zac = {
        showname: data.nick,
        cycleid: insertStr(data.period, 4, "-"),
        ballsHtml: ballsHtml,
        binfo: {
          sum: binfoSum,
        },
        predata: {
          cycleid: insertStr(data.nextseq, 4, "-"),
          opdate: nextTime,
        },
      };
    });
};

getZacData();
</script>

<template>
  <div id="app">
    <div id="theme" class="default">
      <div class="home">
        <!-- 最上方欢迎文本 -->
        <div class="top">
          <div class="content">
            <div class="welcome">
              您好，欢迎光临138菜单，记住一个站，链接通全网，解决站点网址记录烦恼，官方域名138cc.co<span>,备用138cc.us</span>,请惠存！
            </div>
            <div class="huang">【 App <em><a href="/download/138cc.apk" >下载</a></em> 】</div>
          </div>
        </div>
        <!-- 最上方欢迎文本结束 -->
        <div class="top-padding"></div>
        <!-- USDT价格 -->
        <div class="header">
          <div class="content">
            <div class="logo">
              <img
                src="//api.1388cd.com/storage/uploads/logo/OT4rPcs5W5GEMIpP6YBnF2Qnp0ya7w5A7QlA5NX6.png"
                width="281"
                height="93"
              />
            </div>
            <div class="advert rates">
              <div class="row bian">
                <div class="label">
                  币安USDT： <span class="refresh">刷新</span>
                </div>
                <div class="text buy">
                  买入价：¥<span>{{ getRate.bian?.buy || "0.00" }}</span>
                </div>
                <div class="text sell">
                  卖出价：¥<span>{{ getRate.bian?.sell || "0.00" }}</span>
                </div>
                <div class="text site">
                  <a href="https://p2p.binance.com/zh-CN/express/buy/USDT/CNY"
                    >进入币安</a
                  >
                </div>
              </div>
              <div class="row ouyi">
                <div class="label">
                  欧易USDT： <span class="refresh">刷新</span>
                </div>
                <div class="text buy">
                  买入价：¥<span>{{ getRate.ouyi?.buy || "0.00" }}</span>
                </div>
                <div class="text sell">
                  卖出价：¥<span>{{ getRate.ouyi?.sell || "0.00" }}</span>
                </div>
                <div class="text site">
                  <a href="https://www.okx.com/cn/buy-crypto#sourceQuote=cny"
                    >进入欧易</a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- USDT价格结束 -->

        <div class="container" id="heat">
          <!-- out-open 开奖数据开始 -->

          <div class="out-open">
            <div class="date" style="text-align: start">
              <span>{{ headData.xamlh?.showname || "新澳门六合彩" }}</span>
            </div>
            <div class="iframe" v-show="headData.xamlh.showname">
              <div class="com-open">
                <a class="con">
                  <div class="qihao">{{ headData.xamlh?.cycleid || "" }}期</div>
                  <ul
                    class="result"
                    v-html="headData.xamlh?.ballsHtml || ''"
                  ></ul>
                  <div class="sum">
                    总分:{{ headData.xamlh.binfo?.sum || "" }}
                  </div>
                  <div class="nqih">
                    {{ headData.xamlh.predata?.cycleid || "" }}期在{{
                      headData.xamlh.predata?.opdate || ""
                    }}{{ getWeekName(headData.xamlh.predata?.opdate) }}开奖
                  </div>
                </a>
                <div class="sdong">
                  <span @click="getHeadData('xamlh')">手动刷新</span><i>|</i>
                  <a
                    href="https://z02tw.sovparents.com/"
                    target="_blank"
                    class="outlht"
                    >精准资料</a
                  ><i>|</i>
                  <a
                    href="https://kh.12649a.com/?glt=2"
                    target="_blank"
                    class="outlht"
                    >开奖历史</a
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="out-open">
            <div class="date" style="text-align: start">
              <span>{{ headData.bjamlh?.showname || "澳门六合彩" }}</span>
            </div>
            <div class="iframe" v-show="headData.bjamlh.showname">
              <div class="com-open">
                <a class="con">
                  <div class="qihao">
                    {{ headData.bjamlh?.cycleid || "" }}期
                  </div>
                  <ul
                    class="result"
                    v-html="headData.bjamlh?.ballsHtml || ''"
                  ></ul>
                  <div class="sum">
                    总分:{{ headData.bjamlh.binfo?.sum || "" }}
                  </div>
                  <div class="nqih">
                    {{ headData.bjamlh.predata?.cycleid || "" }}期在{{
                      headData.bjamlh.predata?.opdate || ""
                    }}{{ getWeekName(headData.bjamlh.predata?.opdate) }}开奖
                  </div>
                </a>
                <div class="sdong">
                  <span @click="getHeadData('bjamlh')">手动刷新</span><i>|</i>
                  <a
                    href="https://tz-qk0218.6htc.com/lot/bjamlh"
                    target="_blank"
                    class="outlht"
                    >精准资料</a
                  ><i>|</i>
                  <a
                    href="https://lz-tk0254.6hetang.vip/history/bjamlh"
                    target="_blank"
                    class="outlht"
                    >开奖历史</a
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="out-open">
            <div class="date" style="text-align: start">
              <span>{{ headData.lhc?.showname || "香港六合彩" }}</span>
            </div>
            <div class="iframe" v-show="headData.lhc.showname">
              <div class="com-open">
                <a class="con">
                  <div class="qihao">{{ headData.lhc?.cycleid || "" }}期</div>
                  <ul
                    class="result"
                    v-html="headData.lhc?.ballsHtml || ''"
                  ></ul>
                  <div class="sum">
                    总分:{{ headData.lhc.binfo?.sum || "" }}
                  </div>
                  <div class="nqih">
                    {{ headData.lhc.predata?.cycleid || "" }}期在{{
                      headData.lhc.predata?.opdate || ""
                    }}{{ getWeekName(headData.lhc.predata?.opdate) }}开奖
                  </div>
                </a>
                <div class="sdong">
                  <span @click="getHeadData('lhc')">手动刷新</span><i>|</i>
                  <a
                    href="https://w01tk.sovparents.com"
                    target="_blank"
                    class="outlht"
                    >精准资料</a
                  ><i>|</i>
                  <a
                    href="https://kh.12649a.com/?glt=1"
                    target="_blank"
                    class="outlht"
                    >开奖历史</a
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="out-open">
            <div class="date" style="text-align: start">
              <span>{{ headData.twlh?.showname || "台湾六合彩" }}</span>
            </div>
            <div class="iframe" v-show="headData.twlh.showname">
              <div class="com-open">
                <a class="con">
                  <div class="qihao">{{ headData.twlh?.cycleid || "" }}期</div>
                  <ul
                    class="result"
                    v-html="headData.twlh?.ballsHtml || ''"
                  ></ul>
                  <div class="sum">
                    总分:{{ headData.twlh.binfo?.sum || "" }}
                  </div>
                  <div class="nqih">
                    {{ headData.twlh.predata?.cycleid || "" }}期在{{
                      headData.twlh.predata?.opdate || ""
                    }}{{ getWeekName(headData.twlh.predata?.opdate) }}开奖
                  </div>
                </a>
                <div class="sdong">
                  <span @click="getHeadData('twlh')">手动刷新</span><i>|</i>
                  <a
                    href="https://tc-qk0349.tw66666.com/"
                    target="_blank"
                    class="outlht"
                    >精准资料</a
                  ><i>|</i>
                  <a
                    href="http://6hetang.com/history/twlh"
                    target="_blank"
                    class="outlht"
                    >开奖历史</a
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="out-open">
            <div class="date" style="text-align: start">
              <span>{{ headData.lht?.showname || "亚洲六合彩" }}</span>
            </div>
            <div class="iframe" v-show="headData.lht.showname">
              <div class="com-open">
                <a class="con">
                  <div class="qihao">{{ headData.lht?.cycleid || "" }}期</div>
                  <ul
                    class="result"
                    v-html="headData.lht?.ballsHtml || ''"
                  ></ul>
                  <div class="sum">
                    总分:{{ headData.lht.binfo?.sum || "" }}
                  </div>
                  <div class="nqih">
                    {{ headData.lht.predata?.cycleid || "" }}期在{{
                      headData.lht.predata?.opdate || ""
                    }}{{ getWeekName(headData.lht.predata?.opdate) }}开奖
                  </div>
                </a>
                <div class="sdong">
                  <span @click="getHeadData('lht')">手动刷新</span><i>|</i>
                  <a
                    href="https://tz-qk0378.6htc.com/lot/lht"
                    target="_blank"
                    class="outlht"
                    >精准资料</a
                  ><i>|</i>
                  <a
                    href="http://6hetang.com/history/lht"
                    target="_blank"
                    class="outlht"
                    >开奖历史</a
                  >
                </div>
              </div>
            </div>
          </div>

          <div class="out-open">
            <div class="date" style="text-align: start">
              <!-- <span>{{ headData.zac?.showname || "中澳六合彩" }}</span> -->
              <span>中澳六合彩</span>
            </div>
            <div class="iframe" v-show="headData.zac.showname">
              <div class="com-open">
                <a class="con">
                  <div class="qihao">{{ headData.zac?.cycleid || "" }}期</div>
                  <ul
                    class="result"
                    v-html="headData.zac?.ballsHtml || ''"
                  ></ul>
                  <div class="sum">
                    总分:{{ headData.zac.binfo?.sum || "" }}
                  </div>
                  <div class="nqih">
                    {{ headData.zac.predata?.cycleid || "" }}期在{{
                      headData.zac.predata?.opdate || ""
                    }}{{ getWeekName(headData.zac.predata?.opdate) }}开奖
                  </div>
                </a>
                <div class="sdong">
                  <span @click="getZacData()">手动刷新</span><i>|</i>
                  <a
                    href="https://w01tk.sovparents.com/"
                    target="_blank"
                    class="outlht"
                    >精准资料</a
                  ><i>|</i>
                  <a
                    href="https://kh.12649a.com/?glt=3"
                    target="_blank"
                    class="outlht"
                    >开奖历史</a
                  >
                </div>
              </div>
            </div>
          </div>

          <!-- 开奖数据结束 -->

          <!-- 导航开始 -->
          <div class="nav-module">
            <div class="mt10 list-ul">
              <!-- 推荐 -->
              <div class="th">推荐</div>
              <div>
                <ul>
                  <li>
                    <a href="https://138910.com" target="_blank"
                      ><img src="/img/level-1.gif" /><span>138开奖网</span></a
                    >
                  </li>

                  <li>
                    <a onclick="return false"
                      ><img src="/img/level-3.gif" /><span
                        >开奖网(汇总)</span
                      ></a
                    >
                    <div>
                      <div>
                        <a href="http://tw-jc.com/" target="_blank"
                          ><span>台湾六合彩官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://1680kai.com/" target="_blank"
                          ><span>全国开奖网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.bwlc.net/" target="_blank"
                          ><span>北京彩官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.cqcp.net/" target="_blank"
                          ><span>重庆彩官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.gxcaipiao.com.cn/" target="_blank"
                          ><span>广西彩官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://supr10.com/" target="_blank"
                          ><span>超级彩官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://supr10.com/" target="_blank"
                          ><span>闪电彩官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://oo-asia.com/" target="_blank"
                          ><span>新加坡六合官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.hkjc.com/" target="_blank"
                          ><span>香港六合彩官网</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>

                  <li>
                    <a onclick="return false"
                      ><img src="/img/level-3.gif" /><span
                        >比分网(汇总)</span
                      ></a
                    >
                    <div>
                      <div>
                        <a href="http://www.390ko.com/" target="_blank"
                          ><span>90ok极速比分</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://titan007.com" target="_blank"
                          ><span>球探比分</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.leisu.com/" target="_blank"
                          ><span>雷速比分</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://bf.7m.com.cn/" target="_blank"
                          ><span>7M比分</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.spbo1.net/" target="_blank"
                          ><span>体球比分</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://live.611.com/zq" target="_blank"
                          ><span>乐鱼比分</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://bf2.310v.com:3389/" target="_blank"
                          ><span>大赢家比分</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>

                  <li>
                    <a href="https://www.1680kai.com" target="_blank"
                      ><img src="/img/level-1.gif" /><span>1680开奖网</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?393993"
                      target="_blank"
                      ><img src="/img/level-1.gif" /><span>CS1</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?595995"
                      target="_blank"
                      ><img src="/img/level-1.gif" /><span>CS2</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a
                      href="https://www.138dh.vip/urlping.html?599206"
                      target="_blank"
                      ><img src="/img/level-1.gif" /><span>澳迪</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a onclick="return false"
                      ><!----><span>六合彩资料网</span></a
                    >
                    <div>
                      <div>
                        <a href="https://12649a.cc" target="_blank"
                          ><span>线路一</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://12649b.cc" target="_blank"
                          ><span>线路二</span></a
                        >
                        <div></div>
                      </div>
                      <!-- <div><a href="https://678234.com/" target="_blank"><span>管家婆</span></a>
												<div></div>
											</div>
											<div><a href="http://228333.com/" target="_blank"><span>刘伯温</span></a>
												<div></div>
											</div>
											<div><a href="https://paogou444.com/" target="_blank"><span>新版跑狗</span></a>
												<div></div>
											</div>
											<div><a href="http://tw66666.com/" target="_blank"><span>金算盘</span></a>
												<div></div>
											</div>
											<div><a href="http://shengcaituku.com/"
													target="_blank"><span>生才有道</span></a>
												<div></div>
											</div> -->
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <!-- 彩票系统 -->
            <div class="mt10 list-ul">
              <div class="th">彩票系统</div>
              <div>
                <ul>
                  <li>
                    <a
                      href="http://cyedsw-01.ed8880.com/indexcash.php?controller=cashindex&amp;action=loginbytest"
                      target="_blank"
                      ><img src="/img/level-1.gif" /><span>ED地球梦</span></a
                    >
                    <div>
                      <div>
                        <a href="http://138du.com" target="_blank"
                          ><span>ED测试</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>

                  <li>
                    <a onclick="return false"><!----><span>SG双赢</span></a>
                    <div>
                      <div>
                        <a
                          href="https://www.qaz1155.com/?a=19666"
                          target="_blank"
                          ><span>迪士尼I</span></a
                        >
                        <div>
                          <a
                            href="https://www.qaz1155.com/?a=19666"
                            target="_blank"
                            >迪士尼I</a
                          >
                        </div>
                      </div>
                      <div>
                        <a onclick="return false">迪士尼III</a>
                        <div>
                          <a
                            href="https://www.it3313.com?a=19090"
                            target="_blank"
                            >会员1</a
                          ><a
                            href="https://www.it3313.com?a=19090"
                            target="_blank"
                            >会员2</a
                          ><a href="https://dsn3078.com/" target="_blank"
                            >管理1</a
                          >
                        </div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>TC天成</span></a>
                    <div>
                      <div>
                        <a href="https://www.tcgdemo.com/index" target="_blank"
                          ><span>天成演示</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.it3313.com/?a=19090" target="_blank"
                      ><img src="/img/level-3.gif" /><span>迪士尼乐园</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.qaz1155.com/?a=19666"
                          target="_blank"
                          ><span>迪士尼乐园I</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.it3313.com?a=19090" target="_blank"
                          ><span>迪士尼乐园III</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://955.com" target="_blank"
                      ><img src="/img/level-2.gif" /><span>笑傲江湖</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://darknet.lotus.moe/" target="_blank"
                      ><img src="/img/level-2.gif" /><span>泰来2</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">彩票平台</div>
              <div>
                <ul>
                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?720673"
                      target="_blank"
                      ><!----><span>福安(迪士尼)</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a href="https://338910.app" target="_blank"
                      ><img src="/img/level-1.gif" /><span>五湖</span></a
                    >
                  </li>

                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?720675"
                      target="_blank"
                      ><!----><span>GDP(赤金)</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a href="https://338910.app" target="_blank"
                      ><img src="/img/level-1.gif" /><span>名门</span></a
                    >
                  </li>

                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?212552"
                      target="_blank"
                      ><!----><span>粤兴</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?137138"
                      target="_blank"
                      ><img src="/img/level-1.gif" /><span>大时代</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a href="https://338910.app" target="_blank"
                      ><img src="/img/level-3.gif" /><span>新时代</span></a
                    >
                  </li>

                  <li>
                    <a
                      href="https://www.jk88.vip/lines.html?818181"
                      target="_blank"
                      ><!----><span>JK</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a
                      href="https://www.jk88.vip/lines.html?828282"
                      target="_blank"
                      ><!----><span>JK2</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a href="https://338910.app" target="_blank"
                      ><img src="/img/level-3.gif" /><span>KY</span></a
                    >
                  </li>

                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?599333"
                      target="_blank"
                      ><!----><span>万宝</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a href="https://338910.app" target="_blank"
                      ><img src="/img/level-2.gif" /><span>红门</span></a
                    >
                  </li>

                  <li>
                    <a
                      href="https://www.xga88.vip/lines.html?666611"
                      target="_blank"
                      ><img src="/img/level-1.gif" /><span>澳乐</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a
                      href="https://www.xga88.vip/lines.html?666622"
                      target="_blank"
                      ><img src="/img/level-1.gif" /><span>澳乐2</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a onclick="return false"><!----><span>皇冠小圆网</span></a>
                    <div>
                      <div>
                        <a href="https://xy0188.com/" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://xy0288.com/" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://agxy6688.com/" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://agxy6688.com/" target="_blank"
                          ><span>代理2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>

                  <li>
                    <a href="https://338910.app" target="_blank"
                      ><img src="/img/level-1.gif" /><span>大唐</span></a
                    >
                  </li>

                  <li>
                    <a href="https://338910.app" target="_blank"
                      ><img src="/img/level-1.gif" /><span>金盛</span></a
                    >
                  </li>

                  <li>
                    <a href="https://wb998.net/" target="_blank"
                      ><!----><span>寶盈WB</span></a
                    >
                    <div></div>
                  </li>

                  <li>
                    <a href="https://338910.app" target="_blank"
                      ><img src="/img/level-1.gif" /><span>金广</span></a
                    >
                  </li>

                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?199853"
                      target="_blank"
                      ><!----><span>BBA</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?694636"
                      target="_blank"
                      ><!----><span>BBA2</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?582574"
                      target="_blank"
                      ><!----><span>BBA3</span></a
                    >
                    <div></div>
                  </li>

                  <!-- <li><a href="https://338910.app"
											target="_blank"><img src="/img/level-1.gif"><span>五湖</span></a>
                  </li> -->

                  <li>
                    <a onclick="return false"><!----><span>CB(CC彩票)</span></a>
                    <div>
                      <div>
                        <a href="https://rpever2.ccub888.com/" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://h3.elf168.net/" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">足球</div>
              <div>
                <ul>
                  <li>
                    <a onclick="return false"
                      ><img src="/img/level-1.gif" /><span>新2 (皇冠)</span></a
                    >
                    <div>
                      <div>
                        <a href="http://hga050.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://hga030.com" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://199.26.100.166" target="_blank"
                          ><span>会员3</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://66.133.91.116" target="_blank"
                          ><span>会员4</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ag.hga050.com" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ag.hga030.com" target="_blank"
                          ><span>代理2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://123.108.119.60" target="_blank"
                          ><span>代理3</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://old.hg0088.com/" target="_blank"
                          ><span>查旧账</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?202266"
                      target="_blank"
                      ><!----><span>皇冠会员</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a
                      href="https://www.13808.vip/urlping.html?202277"
                      target="_blank"
                      ><!----><span>皇冠代理</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>利 记</span></a>
                    <div>
                      <div>
                        <a href="http://www.u16888.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.beer777.com" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.harybox.com" target="_blank"
                          ><span>会员3</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.sbobet.com" target="_blank"
                          ><span>会员4</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://agent.u16888.com/" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://agent.beer777.com/" target="_blank"
                          ><span>代理2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://agent.harybox.com/" target="_blank"
                          ><span>代理3</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>沙 巴</span></a>
                    <div>
                      <div>
                        <a href="https://www.nova88.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.ultra88.com" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>智 博</span></a>
                    <div>
                      <div>
                        <a href="http://www.isn99.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.isn02.com" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.isn1628.com" target="_blank"
                          ><span>会员3</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.isn999.com" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>平博88</span></a>
                    <div>
                      <div>
                        <a href="http://www.pin9988.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.pinbet88.com" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.ps8989.com" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>PS3838</span></a>
                    <div>
                      <div>
                        <a href="https://www.ps3838.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.ps8989.com" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://isc6688.com" target="_blank"
                      ><!----><span>世博-正水网</span></a
                    >
                    <div>
                      <div>
                        <a href="https://isc6688.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.eb6688.com" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"
                      ><!----><span>众发 反波胆</span></a
                    >
                    <div>
                      <div>
                        <a href="https://zfty8.com/platform" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ag.zfty8.com/" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">娱乐信用</div>
              <div>
                <ul>
                  <li>
                    <a onclick="return false"
                      ><img src="/img/level-2.gif" /><span
                        >博盈(小8娛樂)</span
                      ></a
                    >
                    <div>
                      <div>
                        <a href="https://8.iba228.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://p.iba228.com" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ag.iba228.com" target="_blank"
                          ><span>代理</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"
                      ><!----><span>亚洲商务(泰来)</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.84636970.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.51835319.com" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.bb0001.net" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.bb1006.net" target="_blank"
                          ><span>代理2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>沙 龙</span></a>
                    <div>
                      <div>
                        <a href="http://www.sa36.net" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.sa36.cc" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>(AG亚游)</span></a>
                    <div>
                      <div>
                        <a href="https://www.agvlp07.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.agvlp08.com" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ag.agvlp07.com" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ag.agvlp07.com" target="_blank"
                          ><span>代理2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>欧博</span></a>
                    <div>
                      <div>
                        <a href="https://www.abg999.net/" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.abg888.net/" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ams.abg999.net/" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ams.abg888.net/" target="_blank"
                          ><span>代理2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>(沙龙)</span></a>
                    <div>
                      <div>
                        <a href="https://www.sl036.com" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.sag07.com" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.si37.net" target="_blank"
                          ><span>会员3</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ag.sl036.com" target="_blank"
                          ><span>代理1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ag.sl036.com" target="_blank"
                          ><span>代理2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>亚星</span></a>
                    <div>
                      <div>
                        <a href="https://www.yaxin111.com/" target="_blank"
                          ><span>会员1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.yaxin222.com/" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.mstac.cn/" target="_blank"
                          ><span>代理</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">国 际</div>
              <div>
                <ul>
                  <li>
                    <a
                      href="https://www.yabovip2014.com/?i_code=38857&amp;amp;"
                      target="_blank"
                      ><!----><span>亚 博</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.ybvip8312.vip/" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://www.yabo805.com/?i_code=38857&amp;amp;"
                          target="_blank"
                          ><span>手机端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://www.yb378.app/?i_code=38857&amp;amp;"
                          target="_blank"
                          ><span>App下载</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.yaxin222.com/" target="_blank"
                          ><span>会员2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://cn.mbxlegaseriea.com/?code=35616"
                      target="_blank"
                      ><img src="/img/level-3.gif" /><span>万 博</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://cn.manbetxsjbsports.com/?code=40933"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="http://m.manxwap09.com/account/reg/?code=40933"
                          target="_blank"
                          ><span>手机端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.fun211211.com/igcozv" target="_blank"
                      ><img src="/img/level-3.gif" /><span>乐天堂</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.fun211211.com/igcoka"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://www.fun211211.com/igcozv"
                          target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://www.fun211211.com/igcon"
                          target="_blank"
                          ><span>App下载</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://dsn9300.com/?id=55647" target="_blank"
                      ><img src="/img/level-3.gif" /><span>迪士尼国际</span></a
                    >
                    <div>
                      <div>
                        <a href="http://dsn9300.com/?id=55647" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="http://www.jxfbet2018.com/aff.php?vid=935064"
                      target="_blank"
                      ><!----><span>吉祥坊</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="http://www.jxfbet2018.info/aff.php?vid=935064"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="http://www.wellbet248.net/aff.php?vid=935064"
                          target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://118bd.com/_a4G-kPemReBfSJrXr6jlGGNd7ZgqdRLk/1/"
                      target="_blank"
                      ><!----><span>博狗</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://118bd.com/_a4G-kPemReBfSJrXr6jlGGNd7ZgqdRLk/1/"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">比分网</div>
              <div>
                <ul>
                  <li>
                    <a href="http://live.win007.com" target="_blank"
                      ><img src="/img/level-3.gif" /><span>球探网</span></a
                    >
                    <div>
                      <div>
                        <a href="http://titan007.com" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://bf.7m.com.cn/" target="_blank"
                      ><!----><span>7m比分</span></a
                    >
                    <div>
                      <div>
                        <a href="http://bf.7m.com.cn/" target="_blank"
                          ><span>线路一</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://live.bfw2.com/" target="_blank"
                      ><!----><span>体球网</span></a
                    >
                    <div>
                      <div>
                        <a href="http://live.bfw2.com/" target="_blank"
                          ><span>线路一</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://jindouyun.tv" target="_blank"
                      ><img src="/img/level-3.gif" /><span
                        >足球赛事直播</span
                      ></a
                    >
                    <div>
                      <div>
                        <a href="https://jindouyun.tv" target="_blank"
                          ><span>筋斗云直播</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://sports.longzhu.com/" target="_blank"
                          ><span>龙珠直播</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.360zbz.com/" target="_blank"
                          ><span>360直播</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://live.qq.com/" target="_blank"
                          ><span>企鹅直播</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.leisu.com/" target="_blank"
                      ><!----><span>雷速比分</span></a
                    >
                    <div>
                      <div>
                        <a href="https://live.leisu.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.390ko.com" target="_blank"
                      ><!----><span>90ko极速</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.390ko.com" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.esportlivescore.cn/" target="_blank"
                      ><!----><span>电竞比分网</span></a
                    >
                    <div>
                      <div>
                        <a href="http://bf.310v.net/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://1zplay.com/live" target="_blank"
                      ><!----><span>电竞比分直播</span></a
                    >
                    <div>
                      <div>
                        <a href="http://1zplay.com/live" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://esports.ifeng.com/" target="_blank"
                      ><!----><span>凤凰电竞</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.macauslot.com/slot/html/fixture/fixture.htm?a=0&amp;amp;K=1&amp;amp;l=1"
                          target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"
                      ><!----><span>足球联赛官网</span></a
                    >
                    <div>
                      <div>
                        <a href="https://www.premierleague.com/" target="_blank"
                          ><span>英超官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.dfb.de/index/" target="_blank"
                          ><span>德国足协官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://cn.uefa.com/" target="_blank"
                          ><span>欧洲足协</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.fifa.com/worldcup/" target="_blank"
                          ><span>国际足联</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="http://www.the-afc.com/chs/index.jsp.html"
                          target="_blank"
                          ><span>亚洲足联</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.conmebol.com/" target="_blank"
                          ><span>南美足联</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="http://china.nba.com/index.html?gr=www"
                          target="_blank"
                          ><span>NBA官网</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.cba.gov.cn/" target="_blank"
                          ><span>CBA官网</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">真人棋牌电玩</div>
              <div>
                <ul>
                  <li>
                    <a
                      href="https://cn.manbetxsjbsports.com/?code=40933"
                      target="_blank"
                      ><img src="/img/level-3.gif" /><span>AG亚游</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://cn.manbetxsjbsports.com/?code=40933"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="http://m.manxwap09.com/account/reg/?code=40933"
                          target="_blank"
                          ><span>手机端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="http://www.wanlu901.com/?incode=916971"
                      target="_blank"
                      ><!----><span>千赢国际</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="http://www.wanlu901.com/?incode=916971"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>乐虎电玩</span></a>
                    <div>
                      <div>
                        <a href="http://188vip.lehu156.com" target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://188vip.lehu923.com" target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://cn.manbetxsjbsports.com/?code=40933"
                      target="_blank"
                      ><!----><span>万博电竞</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://cn.manbetxsjbsports.com/?code=40933"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="http://m.manxwap09.com/account/reg/?code=40933"
                          target="_blank"
                          ><span>手机端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">国际综合</div>
              <div>
                <ul>
                  <li>
                    <a
                      href="https://ysb88ee.com/register.html?c=PJZT1"
                      target="_blank"
                      ><!----><span>易胜博</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://ysb88dd.com/register.html?c=PJZT1"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://ysb89.com/register.html?c=PJZT1"
                          target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://www.k875.com/register_phone.htm?palcode=1000879478"
                      target="_blank"
                      ><!----><span>凯发娱乐</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.k875.com/register_phone.htm?palcode=1000879478"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://dsn9300.com/?id=55647" target="_blank"
                      ><!----><span>迪士尼国际</span></a
                    >
                    <div>
                      <div>
                        <a href="http://dsn9300.com/?id=55647" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.rb88858.com/hspplk" target="_blank"
                      ><!----><span>热博</span></a
                    >
                    <div>
                      <div>
                        <a href="https://www.rb88858.com/hsppwq" target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.rb88858.com/hspplk" target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="http://affiliate.udw14.com/register.aspx?referral=61904"
                      target="_blank"
                      ><!----><span>优德w88</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="http://affiliate.udw14.com/register.aspx?referral=61904"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://www.webet779.com/my/lp/sbrng-ftd/?ec=Efrmb0N0oNson_Dq1wMuyWNd7ZgqdRLk"
                      target="_blank"
                      ><!----><span>Webet威博</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.bw585858.com/ctzodp" target="_blank"
                      ><!----><span>必威</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.bw585858.com/ctzodp"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.bw585858.com/ctzo" target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://www.bobvip13.com/1564170654?agent_code=5215"
                      target="_blank"
                      ><!----><span>bob体育</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.bobvip13.com/1564170654?agent_code=5215"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://www.bob2001.com/1562519524?agent_code=5215"
                          target="_blank"
                          ><span>手机端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://www.bob3.app/?agent_code=5215"
                          target="_blank"
                          ><span>App下载</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://aff.sports918.net/sub/75321"
                      target="_blank"
                      ><!----><span>188金宝博</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://aff.sports918.net/sub/75321"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://be9458.com/?intr=1231864" target="_blank"
                      ><!----><span>博九</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://be9458.com/?intr=1231864"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://be9458.com/?aff=1231864"
                          target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.xl18.run?affi=3234" target="_blank"
                      ><!----><span>18luck</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.xl18.run/register?affi=3234"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://m.xl18.run/register?affi=3234"
                          target="_blank"
                          ><span>手机端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://www.xinli18app.net?affi=3234"
                          target="_blank"
                          ><span>App入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://35494.hv9888.com/" target="_blank"
                      ><!----><span>鸿运国际</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.hv9888.com/?aff=35494"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://www.hv838.com//?aff=35494"
                          target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://m.hv9888.com/?aff=35494"
                          target="_blank"
                          ><span>手机端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">其他平台</div>
              <div>
                <ul>
                  <li>
                    <a href="https://ljb04.com/?aff=1233314" target="_blank"
                      ><!----><span>立即博</span></a
                    >
                    <div>
                      <div>
                        <a href="https://ljb19.com/?aff=1233314" target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://ljb04.com/?aff=1233314" target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="http://www.bwin464.com/?intr=bbvv999"
                      target="_blank"
                      ><!----><span>必赢亚洲</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="http://www.bwin464.com/?intr=bbvv999"
                          target="_blank"
                          ><span>PC段入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://t888.t78a.com/" target="_blank"
                      ><!----><span>T博娱乐</span></a
                    >
                    <div>
                      <div>
                        <a href="https://t888.t78a.com/" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://788.awctt.com" target="_blank"
                      ><!----><span>万象城</span></a
                    >
                    <div>
                      <div>
                        <a href="https://788.awctt.com" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.188188688.net/LbQMR2" target="_blank"
                      ><!----><span>利来国际</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.llyq9988.com/LbQMR2" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://m.llyq9988.com/LbQMR2" target="_blank"
                          ><span>手机端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://www.facai9988.com/user-registration?aff=aff_bbvv999"
                      target="_blank"
                      ><!----><span>大班BET</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.facai9988.com/user-registration?aff=aff_bbvv999"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>ASC</span></a>
                    <div>
                      <div>
                        <a
                          href="http://www.ascbet.com/asc/Index.aspx?lang=ch"
                          target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.ss36501.com/?code=4003" target="_blank"
                      ><!----><span>沙龙365</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.ss36502.com/?code=4003"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://www.ss36503.com/?code=4003"
                          target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://jin152.com/7323" target="_blank"
                      ><!----><span>金世豪</span></a
                    >
                    <div>
                      <div>
                        <a href="https://jin152.com/7323" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://22203851.wy117.com" target="_blank"
                      ><!----><span>永乐国际</span></a
                    >
                    <div>
                      <div>
                        <a href="http://22203851.wy117.com" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.xubo118.com/?ag=107188" target="_blank"
                      ><!----><span>旭博</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.xubo118.com/?ag=107188"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://m.xubo118.com/?ag=107188"
                          target="_blank"
                          ><span>手机端入口</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="https://m.xubo118.com/reg-app.php?ag=107188"
                          target="_blank"
                          ><span>App下载</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>优胜客</span></a>
                    <div>
                      <div>
                        <a href="https://www.uni199.com" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.xw363.com/xfbiab" target="_blank"
                      ><!----><span>兴旺</span></a
                    >
                    <div>
                      <div>
                        <a href="https://www.xw363.com/xfbiab" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://www.moscdn.com/proxy.htm?uuii999"
                      target="_blank"
                      ><!----><span>摩斯国际</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.moscdn.com/proxy.htm?uuii999"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://8262.f1318.net" target="_blank"
                      ><!----><span>88娱乐</span></a
                    >
                    <div>
                      <div>
                        <a href="https://8262.f1318.com" target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://8262.fc8882.com" target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>同乐城</span></a>
                    <div>
                      <div>
                        <a
                          href="https://www.tlc133.com/zh/index.htm"
                          target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">打水台</div>
              <div>
                <ul>
                  <li>
                    <a href="http://22203851.wy117.com" target="_blank"
                      ><!----><span>永乐国际</span></a
                    >
                    <div>
                      <div>
                        <a href="http://22203851.wy117.com" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://www.8802ks.com/?palcode=1001145889"
                      target="_blank"
                      ><!----><span>凯时娱乐</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.8802ks.com/?palcode=1001145889"
                          target="_blank"
                          ><span>凯时娱乐</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://www.hg4159.com/indexcc.php?intr=uuii999"
                      target="_blank"
                      ><!----><span>HG新2现金</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.hg4159.com/indexcc.php?intr=uuii999"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="http://www.n8cai.net/SIGN?code=112ED3"
                      target="_blank"
                      ><!----><span>N8娱乐</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="http://www.n8cai.net/SIGN?code=E8FA0F"
                          target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="http://www.n8cai.net/SIGN?code=BB2C05"
                          target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://000blb.com/9553" target="_blank"
                      ><!----><span>百乐博</span></a
                    >
                    <div>
                      <div>
                        <a href="http://000blb.com/9553" target="_blank"
                          ><span>PC端入口1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://000blb.com/9554" target="_blank"
                          ><span>PC端入口2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="http://www.sha22.com/User/Regist?agent=uuii999"
                      target="_blank"
                      ><!----><span>澳门金沙</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="http://www.sha22.com/User/Regist?agent=uuii999"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                      href="https://www.hg95950.com/User/Regist?agent=uuii999"
                      target="_blank"
                      ><!----><span>HG现金</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.hg95950.com/User/Regist?agent=uuii999"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.mgm765.com/?f=uuii999" target="_blank"
                      ><!----><span>美高梅</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.mgm765.com/?f=uuii999"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.gcgc57.com?p=100759" target="_blank"
                      ><!----><span>黄金城</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.gcgc57.com?p=100759" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://66111277.com/?a=9205392" target="_blank"
                      ><!----><span>澳门星际</span></a
                    >
                    <div>
                      <div>
                        <a href="http://66111277.com/?a=9205392" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.42423499.com/?a=9446869" target="_blank"
                      ><!----><span>拉斯维加斯</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="http://www.42423499.com/?a=9446869"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.7751701.com/?p=5719936" target="_blank"
                      ><!----><span>澳门永利</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.7751701.com/?p=5719936"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.jdb.bet/?ak=285076" target="_blank"
                      ><!----><span>金鼎博</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.jdb.bet/?ak=285076" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.rbet88.com/?p=216643" target="_blank"
                      ><!----><span>富易堂</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="https://www.rbet88.com/?p=216643"
                          target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://yz78.cc/?Intr=61161401" target="_blank"
                      ><!----><span>亚洲真人</span></a
                    >
                    <div>
                      <div>
                        <a href="https://yz78.cc/?Intr=61161401" target="_blank"
                          ><span>PC端入口</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">赛 马</div>
              <div>
                <ul>
                  <li>
                    <a onclick="return false"><!----><span>长城</span></a>
                    <div>
                      <div>
                        <a href="https://www.ctb988.net" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://www.ctb988.com" target="_blank"
                          ><span>线路2</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>槟城</span></a>
                    <div>
                      <div>
                        <a href="http://www.penangturfclub.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>澳门马会</span></a>
                    <div>
                      <div>
                        <a
                          href="http://www.mjc.mo/race/info/index.php"
                          target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>香港马会</span></a>
                    <div>
                      <div>
                        <a
                          href="http://betslip.hkjcfootball.com/"
                          target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.horserace88.com/" target="_blank"
                      ><!----><span>赛马资料库</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.horserace88.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">银 行</div>
              <div>
                <ul>
                  <li>
                    <a href="http://www.icbc.com.cn" target="_blank"
                      ><!----><span>工商银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.icbc.com.cn" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.abchina.com" target="_blank"
                      ><!----><span>农业银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.abchina.com" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.ccb.com" target="_blank"
                      ><!----><span>建设银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.ccb.com" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.bankcomm.com" target="_blank"
                      ><!----><span>交通银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.bankcomm.com" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.bank-of-china.com/" target="_blank"
                      ><!----><span>中国银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.bank-of-china.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.cebbank.com/" target="_blank"
                      ><!----><span>光大银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.cebbank.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.cmbc.com.cn/" target="_blank"
                      ><!----><span>民生银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.cmbc.com.cn/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.cgbchina.com.cn/" target="_blank"
                      ><!----><span>广发银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.cgbchina.com.cn/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.cmbchina.com/" target="_blank"
                      ><!----><span>招商银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.cmbchina.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.psbc.com/" target="_blank"
                      ><!----><span>邮政储蓄</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.psbc.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.hxb.com.cn/index1.html" target="_blank"
                      ><!----><span>华夏银行</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="http://www.hxb.com.cn/index1.html"
                          target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.citicbank.com/" target="_blank"
                      ><!----><span>中信银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.citicbank.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.spdb.com.cn/" target="_blank"
                      ><!----><span>浦发银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.spdb.com.cn/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.hxb.com.cn/index.shtml" target="_blank"
                      ><!----><span>华夏银行</span></a
                    >
                    <div>
                      <div>
                        <a
                          href="http://www.hxb.com.cn/index.shtml"
                          target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://bank.pingan.com/" target="_blank"
                      ><!----><span>平安银行</span></a
                    >
                    <div>
                      <div>
                        <a href="http://bank.pingan.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.alipay.com/" target="_blank"
                      ><!----><span>支付宝</span></a
                    >
                    <div>
                      <div>
                        <a href="https://www.alipay.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">电影区</div>
              <div>
                <ul>
                  <li>
                    <a href="https://qingse.one/" target="_blank"
                      ><img src="/img/level-1.gif" /><span
                        >情色网址大全</span
                      ></a
                    >
                    <div>
                      <div>
                        <a href="http://www.91xxxmm.com/n/" target="_blank"
                          ><span>韩国</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.thumbzilla.com/" target="_blank"
                          ><span>西欧</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://pornoid.com/" target="_blank"
                          ><span>东欧</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://fanqianglu.com/" target="_blank"
                          ><span>翻墙撸</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://69vj.com/" target="_blank"
                          ><span>69vj成人</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://ais20.com/" target="_blank"
                          ><span>爱看成人</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://qingse.one/" target="_blank"
                          ><span>情色网站大全</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://whichav.video/" target="_blank"
                          ><span>成人网站大全</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://theporndude.com/" target="_blank"
                          ><span>各类网站大全</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://zootube1.com/" target="_blank"
                          ><span>SM禁</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.rtrt1000.com/" target="_blank"
                      ><img src="/img/level-1.gif" /><span>日本v主题</span></a
                    >
                    <div>
                      <div>
                        <a href="https://www.helloavgirls.com/" target="_blank"
                          ><span>日本成人影片</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="https://www.xvideos.com/" target="_blank"
                          ><span>Sodjav成人</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://avcao.cc/" target="_blank"
                          ><span>AV草</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://4lv7.xyz/" target="_blank"
                          ><span>水中色</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://oiodo.com/" target="_blank"
                          ><span>亚洲成人</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://phim.xxx/" target="_blank"
                          ><span>日本情色大全</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a href="http://wuerav03.com/" target="_blank"
                          ><span>舞儿美眉</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://www.gavbus666.com/" target="_blank"
                      ><!----><span>18禁下载</span></a
                    >
                    <div>
                      <div>
                        <a href="https://www.gavbus666.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://e-hentai.fbk.tokyo/" target="_blank"
                      ><!----><span>漫画集锦</span></a
                    >
                    <div>
                      <div>
                        <a href="http://e-hentai.fbk.tokyo/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>社区论坛</span></a>
                    <div>
                      <div>
                        <a href="http://jinersi.tk/index.php" target="_blank"
                          ><span>草榴社区</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.iqiyi.com/" target="_blank"
                      ><!----><span>爱奇艺</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.iqiyi.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="https://v.qq.com/" target="_blank"
                      ><!----><span>腾讯视频</span></a
                    >
                    <div>
                      <div>
                        <a href="https://v.qq.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.youku.com/" target="_blank"
                      ><!----><span>优酷视频</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.youku.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">实用地址</div>
              <div>
                <ul>
                  <li>
                    <a href="http://www.ip138.com/" target="_blank"
                      ><!----><span>IP地址查询</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.ip138.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.chakahao.com/" target="_blank"
                      ><!----><span>银行卡归属地</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.chakahao.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.ip138.com/" target="_blank"
                      ><!----><span>综合查询</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.ip138.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.ip138.com/" target="_blank"
                      ><!----><span>手机号查询</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.ip138.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.zgjiemeng.com" target="_blank"
                      ><!----><span>周公解梦</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.zgjiemeng.com" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://id.weixingmap.com/" target="_blank"
                      ><!----><span>身份证查询</span></a
                    >
                    <div>
                      <div>
                        <a href="http://id.weixingmap.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://www.guoguo-app.com/" target="_blank"
                      ><!----><span>查快递</span></a
                    >
                    <div>
                      <div>
                        <a href="http://www.guoguo-app.com/" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a href="http://chaxun.weizhang8.cn" target="_blank"
                      ><!----><span>车辆违章查询</span></a
                    >
                    <div>
                      <div>
                        <a href="http://chaxun.weizhang8.cn" target="_blank"
                          ><span>线路1</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">新 闻</div>
              <div>
                <ul>
                  <li>
                    <a href="https://www.toutiao.com/" target="_blank"
                      ><!----><span>今日头条</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://news.163.com/" target="_blank"
                      ><!----><span>网易新闻</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://news.sina.com.cn/" target="_blank"
                      ><!----><span>新浪新闻</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.ifeng.com/" target="_blank"
                      ><!----><span>凤凰新闻</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://news.sohu.com/" target="_blank"
                      ><!----><span>搜狐新闻</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://news.qq.com/" target="_blank"
                      ><!----><span>腾讯新闻</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.people.com.cn/" target="_blank"
                      ><!----><span>人民网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.xinhuanet.com/" target="_blank"
                      ><!----><span>新华网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.81.cn/" target="_blank"
                      ><!----><span>中国军网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a
                      href="https://hk.on.cc/hk/news/index.html"
                      target="_blank"
                      ><!----><span>香港即时新闻</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.ce.cn/" target="_blank"
                      ><!----><span>经济网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.zaobao.com/" target="_blank"
                      ><!----><span>联合早报</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.southcn.com/" target="_blank"
                      ><!----><span>南方网</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">通 讯</div>
              <div>
                <ul>
                  <li>
                    <a href="https://im.qq.com/index.shtml" target="_blank"
                      ><!----><span>腾讯QQ</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://weixin.qq.com/" target="_blank"
                      ><!----><span>微信</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.skype.com/zh-Hans/" target="_blank"
                      ><!----><span>skype</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://tantanapp.com/" target="_blank"
                      ><!----><span>探探</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.yy.com/yy8/" target="_blank"
                      ><!----><span>YY语音</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">搜索引擎</div>
              <div>
                <ul>
                  <li>
                    <a href="https://www.google.com.hk" target="_blank"
                      ><!----><span>谷歌搜索</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.baidu.com/" target="_blank"
                      ><!----><span>百度搜索</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.bing.com/?mkt=zh-CN" target="_blank"
                      ><!----><span>微软搜索</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a
                      href="https://sg.search.yahoo.com/?guccounter=1"
                      target="_blank"
                      ><!----><span>Yahoo搜索</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.sogou.com/" target="_blank"
                      ><!----><span>搜狗搜索</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.so.com/" target="_blank"
                      ><!----><span>360搜索</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">邮 箱</div>
              <div>
                <ul>
                  <li>
                    <a href="https://mail.qq.com/" target="_blank"
                      ><!----><span>QQ邮箱</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://mail.163.com/" target="_blank"
                      ><!----><span>163邮箱</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://accounts.google.com" target="_blank"
                      ><!----><span>谷歌邮箱</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://mail.sina.com.cn/" target="_blank"
                      ><!----><span>新浪邮箱</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://mail.126.com/" target="_blank"
                      ><!----><span>126邮箱</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://mail.sohu.com/fe/" target="_blank"
                      ><!----><span>搜狐邮箱</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">交友平台</div>
              <div>
                <ul>
                  <li>
                    <a href="http://www.jiayuan.com/" target="_blank"
                      ><!----><span>世纪佳缘</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.baihe.com" target="_blank"
                      ><!----><span>百合网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://guangdong.zhenai.com/" target="_blank"
                      ><!----><span>珍爱网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a
                      href="http://www.ipart.cn/portability/ad_reception.php"
                      target="_blank"
                      ><!----><span>爱情公寓</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.zhiji.com/" target="_blank"
                      ><!----><span>知己网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://yuehui.163.com/" target="_blank"
                      ><!----><span>同城约会</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.supei.com/" target="_blank"
                      ><!----><span>速配网</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">旅 游</div>
              <div>
                <ul>
                  <li>
                    <a href="http://www.ctrip.com/" target="_blank"
                      ><!----><span>携 程</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.tuniu.com/" target="_blank"
                      ><!----><span>途 牛</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.qunar.com/" target="_blank"
                      ><!----><span>去哪儿</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.elong.com/" target="_blank"
                      ><!----><span>艺 龙</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.lotour.com/" target="_blank"
                      ><!----><span>乐 途</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.17u.com/" target="_blank"
                      ><!----><span>同程网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.mangocity.com/" target="_blank"
                      ><!----><span>芒果旅游</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">直播平台</div>
              <div>
                <ul>
                  <li>
                    <a href="https://www.douyin.com/" target="_blank"
                      ><!----><span>抖 音</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.douyu.com/" target="_blank"
                      ><!----><span>斗 鱼</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.huya.com/" target="_blank"
                      ><!----><span>虎 牙</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.yizhibo.com/" target="_blank"
                      ><!----><span>一直播</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.yy.com/" target="_blank"
                      ><!----><span>YY直播</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.longzhu.com/" target="_blank"
                      ><!----><span>龙珠直播</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.showself.com/" target="_blank"
                      ><!----><span>秀色直播</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.6.cn/" target="_blank"
                      ><!----><span>六间房</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">音 乐</div>
              <div>
                <ul>
                  <li>
                    <a href="https://music.163.com/" target="_blank"
                      ><!----><span>网易云</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://y.qq.com/" target="_blank"
                      ><!----><span>QQ音乐</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.kugou.com/" target="_blank"
                      ><!----><span>酷 狗</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.kuwo.cn/" target="_blank"
                      ><!----><span>酷我音乐</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://music.taihe.com/" target="_blank"
                      ><!----><span>千千音乐</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.djkk.com/" target="_blank"
                      ><!----><span>dj嗨嗨网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.dj.net/?ks321com" target="_blank"
                      ><!----><span>DJ音乐</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.xiami.com/" target="_blank"
                      ><!----><span>虾米音乐</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">汽车网</div>
              <div>
                <ul>
                  <li>
                    <a
                      href="https://www.autohome.com.cn/beijing/"
                      target="_blank"
                      ><!----><span>汽车之家</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.pcauto.com.cn/" target="_blank"
                      ><!----><span>太平洋汽车</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://beijing.bitauto.com/" target="_blank"
                      ><!----><span>易车网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://auto.sina.com.cn/" target="_blank"
                      ><!----><span>新浪汽车</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.jxedt.com/" target="_blank"
                      ><!----><span>驾校一点通</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://auto.ifeng.com/" target="_blank"
                      ><!----><span>凤凰汽车</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://auto.qq.com/" target="_blank"
                      ><!----><span>腾讯汽车</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">财 经</div>
              <div>
                <ul>
                  <li>
                    <a href="http://www.eastmoney.com/" target="_blank"
                      ><!----><span>东方财富</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://finance.ifeng.com/" target="_blank"
                      ><!----><span>凤凰财经</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.jrj.com.cn/" target="_blank"
                      ><!----><span>金融界</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://finance.sina.com.cn/" target="_blank"
                      ><!----><span>新浪财经</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.stockstar.com/" target="_blank"
                      ><!----><span>证券之星</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.yicai.com/" target="_blank"
                      ><!----><span>第一财经</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a onclick="return false"><!----><span>第六财经</span></a>
                    <div>
                      <div>
                        <a
                          href="http://da1258.com/dashboard/login"
                          target="_blank"
                          ><span>入口一</span></a
                        >
                        <div></div>
                      </div>
                      <div>
                        <a
                          href="http://hktc-admin.cbc666.cc/dashboard/"
                          target="_blank"
                          ><span>入口二</span></a
                        >
                        <div></div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">生 活</div>
              <div>
                <ul>
                  <li>
                    <a href="http://www.meituan.com/" target="_blank"
                      ><!----><span>美 团</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://bj.58.com/" target="_blank"
                      ><!----><span>58同城</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://bj.ganji.com/" target="_blank"
                      ><!----><span>赶集网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.fang.com/" target="_blank"
                      ><!----><span>房天下</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.dianping.com/" target="_blank"
                      ><!----><span>大众点评</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.tianyancha.com/" target="_blank"
                      ><!----><span>天眼查</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.baixing.com/" target="_blank"
                      ><!----><span>百姓网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.xiachufang.com/" target="_blank"
                      ><!----><span>下厨房</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="mt10 list-ul">
              <div class="th">购 物</div>
              <div>
                <ul>
                  <li>
                    <a href="https://www.taobao.com/" target="_blank"
                      ><!----><span>淘宝网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.suning.com" target="_blank"
                      ><!----><span>苏宁易购</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.jd.com" target="_blank"
                      ><!----><span>京 东</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://ju.taobao.com" target="_blank"
                      ><!----><span>聚划算</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.pinduoduo.com/" target="_blank"
                      ><!----><span>拼多多</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="http://www.dangdang.com/" target="_blank"
                      ><!----><span>当当网</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.yhd.com/" target="_blank"
                      ><!----><span>一号店</span></a
                    >
                    <div></div>
                  </li>
                  <li>
                    <a href="https://www.mogu.com/" target="_blank"
                      ><!----><span>蘑菇街</span></a
                    >
                    <div></div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- 导航结束 -->
          <div class="mt10 links">
            <div class="th">友情链接</div>
            <div class="td"><a></a><a></a></div>
          </div>
        </div>
        <!-- 底部开始 -->
        <div class="footer">
          <p>
            郑重声明：网上有诈骗，所有在本站刊登广告的网站和内容，一概与本站无关，请各位网友密切注意!
          </p>
          <p>
            本网站所登载
            的广告，均为广告客户的个人意见和表达方式，与本网站无任何关系。
          </p>
          <p>
            与本网站链接的广告，均不得涉及反动、迷信、淫秽、赌博等有害内容，不得
            违反国家法律规定，如有违者，本网站有权随时予以删除，
          </p>
          <p>
            并保留与有关部门合作追究的权利。本网站拒绝为任何具有赌博性质的组织进行广告链接。
          </p>
          <p class="col">
            Copyright © 2018 AAAAA Inc. All Rights Reserved. 闽ICP备564215498号
          </p>
        </div>
        <!-- 底部结束 -->
        <div class="kefu" style="display: none">
          <div class="btn"><b></b></div>
          <div class="mos" style="right: -144px">
            <div class="tit"><b></b><em></em></div>
            <ul>
              <li>
                <a
                  title="点击这里给我发消息"
                  href="http://wpa.qq.com/msgrd?v=3&amp;uin=&amp;site=qq&amp;menu=yes"
                  target="_blank"
                  ><em>客服咨询</em
                  ><span class="qqserver-service-alert">QQ</span></a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
audio,
video {
  display: inline-block;
  /* *display: inline; */
  /* *zoom: 1; */
}

img {
  border: 0;
}

input,
button,
textarea,
select {
  font-family: inherit;
  font-size: inherit;
  font-weight: inherit;
  outline: none;
}

textarea {
  resize: none;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

em {
  font-style: normal;
}

ul,
li {
  list-style: none;
}

i {
  font-style: normal;
}

* {
  margin: 0;
  padding: 0;
}

html,
html body {
  height: 100%;
}

html body {
  font-family: Microsoft YaHei, Helvetica Neue, Helvetica, Arial, sans-serif;
  background: #ffffff;
  font-size: 12px;
  color: #333333;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

input[type="number"] {
  /* -moz-appearance: textfield; */
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
}

select {
  padding: 3px 0 3px 3px;
  border: 1px solid #cccccc;
  color: #666;
  -webkit-box-shadow: 0 -1px 0 0 #cccccc;
  -o-box-shadow: 0 -1px 0 0 #cccccc;
  -ms-box-shadow: 0 -1px 0 0 #cccccc;
  box-shadow: 0 1px 2px 0 #e8e8e8 inset;
}

.mt10 {
  margin-top: 10px;
}

#app {
  height: 100%;
  font-size: 12px;
}

.content {
  margin: 0 auto;
  width: 982px;
}

.van-toast {
  min-width: 200px;
  min-height: 120px;
}
</style>
<style>
html {
  -webkit-tap-highlight-color: transparent;
}

body {
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue", Helvetica,
    Segoe UI, Arial, Roboto, "PingFang SC", "miui", "Hiragino Sans GB",
    "Microsoft Yahei", sans-serif;
}

a {
  text-decoration: none;
}

input,
button,
textarea {
  color: inherit;
  font: inherit;
}

a:focus,
input:focus,
button:focus,
textarea:focus,
[class*="van-"]:focus {
  outline: none;
}

ol,
ul {
  margin: 0;
  padding: 0;
  list-style: none;
}

.van-ellipsis {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.van-multi-ellipsis--l2 {
  display: -webkit-box;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-line-clamp: 2;
  /* autoprefixer: ignore next */
  -webkit-box-orient: vertical;
}

.van-multi-ellipsis--l3 {
  display: -webkit-box;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-line-clamp: 3;
  /* autoprefixer: ignore next */
  -webkit-box-orient: vertical;
}

.van-clearfix::after {
  display: table;
  clear: both;
  content: "";
}

[class*="van-hairline"]::after {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  top: -50%;
  right: -50%;
  bottom: -50%;
  left: -50%;
  border: 0 solid #ebedf0;
  -webkit-transform: scale(0.5);
  transform: scale(0.5);
}

.van-hairline,
.van-hairline--top,
.van-hairline--left,
.van-hairline--right,
.van-hairline--bottom,
.van-hairline--surround,
.van-hairline--top-bottom {
  position: relative;
}

.van-hairline--top::after {
  border-top-width: 1px;
}

.van-hairline--left::after {
  border-left-width: 1px;
}

.van-hairline--right::after {
  border-right-width: 1px;
}

.van-hairline--bottom::after {
  border-bottom-width: 1px;
}

.van-hairline--top-bottom::after,
.van-hairline-unset--top-bottom::after {
  border-width: 1px 0;
}

.van-hairline--surround::after {
  border-width: 1px;
}

@-webkit-keyframes van-slide-up-enter {
  from {
    -webkit-transform: translate3d(0, 100%, 0);
    transform: translate3d(0, 100%, 0);
  }
}

@keyframes van-slide-up-enter {
  from {
    -webkit-transform: translate3d(0, 100%, 0);
    transform: translate3d(0, 100%, 0);
  }
}

@-webkit-keyframes van-slide-up-leave {
  to {
    -webkit-transform: translate3d(0, 100%, 0);
    transform: translate3d(0, 100%, 0);
  }
}

@keyframes van-slide-up-leave {
  to {
    -webkit-transform: translate3d(0, 100%, 0);
    transform: translate3d(0, 100%, 0);
  }
}

@-webkit-keyframes van-slide-down-enter {
  from {
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
}

@keyframes van-slide-down-enter {
  from {
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
}

@-webkit-keyframes van-slide-down-leave {
  to {
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
}

@keyframes van-slide-down-leave {
  to {
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
}

@-webkit-keyframes van-slide-left-enter {
  from {
    -webkit-transform: translate3d(-100%, 0, 0);
    transform: translate3d(-100%, 0, 0);
  }
}

@keyframes van-slide-left-enter {
  from {
    -webkit-transform: translate3d(-100%, 0, 0);
    transform: translate3d(-100%, 0, 0);
  }
}

@-webkit-keyframes van-slide-left-leave {
  to {
    -webkit-transform: translate3d(-100%, 0, 0);
    transform: translate3d(-100%, 0, 0);
  }
}

@keyframes van-slide-left-leave {
  to {
    -webkit-transform: translate3d(-100%, 0, 0);
    transform: translate3d(-100%, 0, 0);
  }
}

@-webkit-keyframes van-slide-right-enter {
  from {
    -webkit-transform: translate3d(100%, 0, 0);
    transform: translate3d(100%, 0, 0);
  }
}

@keyframes van-slide-right-enter {
  from {
    -webkit-transform: translate3d(100%, 0, 0);
    transform: translate3d(100%, 0, 0);
  }
}

@-webkit-keyframes van-slide-right-leave {
  to {
    -webkit-transform: translate3d(100%, 0, 0);
    transform: translate3d(100%, 0, 0);
  }
}

@keyframes van-slide-right-leave {
  to {
    -webkit-transform: translate3d(100%, 0, 0);
    transform: translate3d(100%, 0, 0);
  }
}

@-webkit-keyframes van-fade-in {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@keyframes van-fade-in {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@-webkit-keyframes van-fade-out {
  from {
    opacity: 1;
  }

  to {
    opacity: 0;
  }
}

@keyframes van-fade-out {
  from {
    opacity: 1;
  }

  to {
    opacity: 0;
  }
}

@-webkit-keyframes van-rotate {
  from {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }

  to {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@keyframes van-rotate {
  from {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }

  to {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

.van-fade-enter-active {
  -webkit-animation: 0.3s van-fade-in both ease-out;
  animation: 0.3s van-fade-in both ease-out;
}

.van-fade-leave-active {
  -webkit-animation: 0.3s van-fade-out both ease-in;
  animation: 0.3s van-fade-out both ease-in;
}

.van-slide-up-enter-active {
  -webkit-animation: van-slide-up-enter 0.3s both ease-out;
  animation: van-slide-up-enter 0.3s both ease-out;
}

.van-slide-up-leave-active {
  -webkit-animation: van-slide-up-leave 0.3s both ease-in;
  animation: van-slide-up-leave 0.3s both ease-in;
}

.van-slide-down-enter-active {
  -webkit-animation: van-slide-down-enter 0.3s both ease-out;
  animation: van-slide-down-enter 0.3s both ease-out;
}

.van-slide-down-leave-active {
  -webkit-animation: van-slide-down-leave 0.3s both ease-in;
  animation: van-slide-down-leave 0.3s both ease-in;
}

.van-slide-left-enter-active {
  -webkit-animation: van-slide-left-enter 0.3s both ease-out;
  animation: van-slide-left-enter 0.3s both ease-out;
}

.van-slide-left-leave-active {
  -webkit-animation: van-slide-left-leave 0.3s both ease-in;
  animation: van-slide-left-leave 0.3s both ease-in;
}

.van-slide-right-enter-active {
  -webkit-animation: van-slide-right-enter 0.3s both ease-out;
  animation: van-slide-right-enter 0.3s both ease-out;
}

.van-slide-right-leave-active {
  -webkit-animation: van-slide-right-leave 0.3s both ease-in;
  animation: van-slide-right-leave 0.3s both ease-in;
}

.van-overlay {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
}

.van-info {
  position: absolute;
  top: 0;
  right: 0;
  box-sizing: border-box;
  min-width: 16px;
  padding: 0 3px;
  color: #fff;
  font-weight: 500;
  font-size: 12px;
  font-family: -apple-system-font, Helvetica Neue, Arial, sans-serif;
  line-height: 1.2;
  text-align: center;
  background-color: #ee0a24;
  border: 1px solid #fff;
  border-radius: 16px;
  -webkit-transform: translate(50%, -50%);
  transform: translate(50%, -50%);
  -webkit-transform-origin: 100%;
  transform-origin: 100%;
}

.van-info--dot {
  width: 8px;
  min-width: 0;
  height: 8px;
  background-color: #ee0a24;
  border-radius: 100%;
}

.van-sidebar-item {
  position: relative;
  display: block;
  box-sizing: border-box;
  padding: 20px 12px;
  overflow: hidden;
  color: #323233;
  font-size: 14px;
  line-height: 20px;
  background-color: #f7f8fa;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}

.van-sidebar-item:active {
  background-color: #f2f3f5;
}

.van-sidebar-item__text {
  position: relative;
  display: inline-block;
  word-break: break-all;
}

.van-sidebar-item:not(:last-child)::after {
  border-bottom-width: 1px;
}

.van-sidebar-item--select {
  color: #323233;
  font-weight: 500;
}

.van-sidebar-item--select,
.van-sidebar-item--select:active {
  background-color: #fff;
}

.van-sidebar-item--select::before {
  position: absolute;
  top: 50%;
  left: 0;
  width: 4px;
  height: 16px;
  background-color: #ee0a24;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
  content: "";
}

.van-sidebar-item--disabled {
  color: #c8c9cc;
  cursor: not-allowed;
}

.van-sidebar-item--disabled:active {
  background-color: #f7f8fa;
}

/* stylelint-disable selector-pseudo-element-colon-notation */
/* stylelint-disable font-family-no-missing-generic-family-keyword */
.van-icon {
  position: relative;
  display: inline-block;
  font: normal normal normal 14px/1 "vant-icon";
  font-size: inherit;
  text-rendering: auto;
  -webkit-font-smoothing: antialiased;
}

.van-icon:before {
  display: inline-block;
}

.van-icon-exchange:before {
  content: "\e6af";
}

.van-icon-eye:before {
  content: "\e6b0";
}

.van-icon-enlarge:before {
  content: "\e6b1";
}

.van-icon-expand-o:before {
  content: "\e6b2";
}

.van-icon-eye-o:before {
  content: "\e6b3";
}

.van-icon-expand:before {
  content: "\e6b4";
}

.van-icon-filter-o:before {
  content: "\e6b5";
}

.van-icon-fire:before {
  content: "\e6b6";
}

.van-icon-fail:before {
  content: "\e6b7";
}

.van-icon-failure:before {
  content: "\e6b8";
}

.van-icon-fire-o:before {
  content: "\e6b9";
}

.van-icon-flag-o:before {
  content: "\e6ba";
}

.van-icon-font:before {
  content: "\e6bb";
}

.van-icon-font-o:before {
  content: "\e6bc";
}

.van-icon-gem-o:before {
  content: "\e6bd";
}

.van-icon-flower-o:before {
  content: "\e6be";
}

.van-icon-gem:before {
  content: "\e6bf";
}

.van-icon-gift-card:before {
  content: "\e6c0";
}

.van-icon-friends:before {
  content: "\e6c1";
}

.van-icon-friends-o:before {
  content: "\e6c2";
}

.van-icon-gold-coin:before {
  content: "\e6c3";
}

.van-icon-gold-coin-o:before {
  content: "\e6c4";
}

.van-icon-good-job-o:before {
  content: "\e6c5";
}

.van-icon-gift:before {
  content: "\e6c6";
}

.van-icon-gift-o:before {
  content: "\e6c7";
}

.van-icon-gift-card-o:before {
  content: "\e6c8";
}

.van-icon-good-job:before {
  content: "\e6c9";
}

.van-icon-home-o:before {
  content: "\e6ca";
}

.van-icon-goods-collect:before {
  content: "\e6cb";
}

.van-icon-graphic:before {
  content: "\e6cc";
}

.van-icon-goods-collect-o:before {
  content: "\e6cd";
}

.van-icon-hot-o:before {
  content: "\e6ce";
}

.van-icon-info:before {
  content: "\e6cf";
}

.van-icon-hotel-o:before {
  content: "\e6d0";
}

.van-icon-info-o:before {
  content: "\e6d1";
}

.van-icon-hot-sale-o:before {
  content: "\e6d2";
}

.van-icon-hot:before {
  content: "\e6d3";
}

.van-icon-like:before {
  content: "\e6d4";
}

.van-icon-idcard:before {
  content: "\e6d5";
}

.van-icon-invitation:before {
  content: "\e6d6";
}

.van-icon-like-o:before {
  content: "\e6d7";
}

.van-icon-hot-sale:before {
  content: "\e6d8";
}

.van-icon-location-o:before {
  content: "\e6d9";
}

.van-icon-location:before {
  content: "\e6da";
}

.van-icon-label:before {
  content: "\e6db";
}

.van-icon-lock:before {
  content: "\e6dc";
}

.van-icon-label-o:before {
  content: "\e6dd";
}

.van-icon-map-marked:before {
  content: "\e6de";
}

.van-icon-logistics:before {
  content: "\e6df";
}

.van-icon-manager:before {
  content: "\e6e0";
}

.van-icon-more:before {
  content: "\e6e1";
}

.van-icon-live:before {
  content: "\e6e2";
}

.van-icon-manager-o:before {
  content: "\e6e3";
}

.van-icon-medal:before {
  content: "\e6e4";
}

.van-icon-more-o:before {
  content: "\e6e5";
}

.van-icon-music-o:before {
  content: "\e6e6";
}

.van-icon-music:before {
  content: "\e6e7";
}

.van-icon-new-arrival-o:before {
  content: "\e6e8";
}

.van-icon-medal-o:before {
  content: "\e6e9";
}

.van-icon-new-o:before {
  content: "\e6ea";
}

.van-icon-free-postage:before {
  content: "\e6eb";
}

.van-icon-newspaper-o:before {
  content: "\e6ec";
}

.van-icon-new-arrival:before {
  content: "\e6ed";
}

.van-icon-minus:before {
  content: "\e6ee";
}

.van-icon-orders-o:before {
  content: "\e6ef";
}

.van-icon-new:before {
  content: "\e6f0";
}

.van-icon-paid:before {
  content: "\e6f1";
}

.van-icon-notes-o:before {
  content: "\e6f2";
}

.van-icon-other-pay:before {
  content: "\e6f3";
}

.van-icon-pause-circle:before {
  content: "\e6f4";
}

.van-icon-pause:before {
  content: "\e6f5";
}

.van-icon-pause-circle-o:before {
  content: "\e6f6";
}

.van-icon-peer-pay:before {
  content: "\e6f7";
}

.van-icon-pending-payment:before {
  content: "\e6f8";
}

.van-icon-passed:before {
  content: "\e6f9";
}

.van-icon-plus:before {
  content: "\e6fa";
}

.van-icon-phone-circle-o:before {
  content: "\e6fb";
}

.van-icon-phone-o:before {
  content: "\e6fc";
}

.van-icon-printer:before {
  content: "\e6fd";
}

.van-icon-photo-fail:before {
  content: "\e6fe";
}

.van-icon-phone:before {
  content: "\e6ff";
}

.van-icon-photo-o:before {
  content: "\e700";
}

.van-icon-play-circle:before {
  content: "\e701";
}

.van-icon-play:before {
  content: "\e702";
}

.van-icon-phone-circle:before {
  content: "\e703";
}

.van-icon-point-gift-o:before {
  content: "\e704";
}

.van-icon-point-gift:before {
  content: "\e705";
}

.van-icon-play-circle-o:before {
  content: "\e706";
}

.van-icon-shrink:before {
  content: "\e707";
}

.van-icon-photo:before {
  content: "\e708";
}

.van-icon-qr:before {
  content: "\e709";
}

.van-icon-qr-invalid:before {
  content: "\e70a";
}

.van-icon-question-o:before {
  content: "\e70b";
}

.van-icon-revoke:before {
  content: "\e70c";
}

.van-icon-replay:before {
  content: "\e70d";
}

.van-icon-service:before {
  content: "\e70e";
}

.van-icon-question:before {
  content: "\e70f";
}

.van-icon-search:before {
  content: "\e710";
}

.van-icon-refund-o:before {
  content: "\e711";
}

.van-icon-service-o:before {
  content: "\e712";
}

.van-icon-scan:before {
  content: "\e713";
}

.van-icon-share:before {
  content: "\e714";
}

.van-icon-send-gift-o:before {
  content: "\e715";
}

.van-icon-share-o:before {
  content: "\e716";
}

.van-icon-setting:before {
  content: "\e717";
}

.van-icon-points:before {
  content: "\e718";
}

.van-icon-photograph:before {
  content: "\e719";
}

.van-icon-shop:before {
  content: "\e71a";
}

.van-icon-shop-o:before {
  content: "\e71b";
}

.van-icon-shop-collect-o:before {
  content: "\e71c";
}

.van-icon-shop-collect:before {
  content: "\e71d";
}

.van-icon-smile:before {
  content: "\e71e";
}

.van-icon-shopping-cart-o:before {
  content: "\e71f";
}

.van-icon-sign:before {
  content: "\e720";
}

.van-icon-sort:before {
  content: "\e721";
}

.van-icon-star-o:before {
  content: "\e722";
}

.van-icon-smile-comment-o:before {
  content: "\e723";
}

.van-icon-stop:before {
  content: "\e724";
}

.van-icon-stop-circle-o:before {
  content: "\e725";
}

.van-icon-smile-o:before {
  content: "\e726";
}

.van-icon-star:before {
  content: "\e727";
}

.van-icon-success:before {
  content: "\e728";
}

.van-icon-stop-circle:before {
  content: "\e729";
}

.van-icon-records:before {
  content: "\e72a";
}

.van-icon-shopping-cart:before {
  content: "\e72b";
}

.van-icon-tosend:before {
  content: "\e72c";
}

.van-icon-todo-list:before {
  content: "\e72d";
}

.van-icon-thumb-circle-o:before {
  content: "\e72e";
}

.van-icon-thumb-circle:before {
  content: "\e72f";
}

.van-icon-umbrella-circle:before {
  content: "\e730";
}

.van-icon-underway:before {
  content: "\e731";
}

.van-icon-upgrade:before {
  content: "\e732";
}

.van-icon-todo-list-o:before {
  content: "\e733";
}

.van-icon-tv-o:before {
  content: "\e734";
}

.van-icon-underway-o:before {
  content: "\e735";
}

.van-icon-user-o:before {
  content: "\e736";
}

.van-icon-vip-card-o:before {
  content: "\e737";
}

.van-icon-vip-card:before {
  content: "\e738";
}

.van-icon-send-gift:before {
  content: "\e739";
}

.van-icon-wap-home:before {
  content: "\e73a";
}

.van-icon-wap-nav:before {
  content: "\e73b";
}

.van-icon-volume-o:before {
  content: "\e73c";
}

.van-icon-video:before {
  content: "\e73d";
}

.van-icon-wap-home-o:before {
  content: "\e73e";
}

.van-icon-volume:before {
  content: "\e73f";
}

.van-icon-warning:before {
  content: "\e740";
}

.van-icon-weapp-nav:before {
  content: "\e741";
}

.van-icon-wechat-pay:before {
  content: "\e742";
}

.van-icon-warning-o:before {
  content: "\e743";
}

.van-icon-wechat:before {
  content: "\e744";
}

.van-icon-setting-o:before {
  content: "\e745";
}

.van-icon-youzan-shield:before {
  content: "\e746";
}

.van-icon-warn-o:before {
  content: "\e747";
}

.van-icon-smile-comment:before {
  content: "\e748";
}

.van-icon-user-circle-o:before {
  content: "\e749";
}

.van-icon-video-o:before {
  content: "\e74a";
}

.van-icon-add-square:before {
  content: "\e65c";
}

.van-icon-add:before {
  content: "\e65d";
}

.van-icon-arrow-down:before {
  content: "\e65e";
}

.van-icon-arrow-up:before {
  content: "\e65f";
}

.van-icon-arrow:before {
  content: "\e660";
}

.van-icon-after-sale:before {
  content: "\e661";
}

.van-icon-add-o:before {
  content: "\e662";
}

.van-icon-alipay:before {
  content: "\e663";
}

.van-icon-ascending:before {
  content: "\e664";
}

.van-icon-apps-o:before {
  content: "\e665";
}

.van-icon-aim:before {
  content: "\e666";
}

.van-icon-award:before {
  content: "\e667";
}

.van-icon-arrow-left:before {
  content: "\e668";
}

.van-icon-award-o:before {
  content: "\e669";
}

.van-icon-audio:before {
  content: "\e66a";
}

.van-icon-bag-o:before {
  content: "\e66b";
}

.van-icon-balance-list:before {
  content: "\e66c";
}

.van-icon-back-top:before {
  content: "\e66d";
}

.van-icon-bag:before {
  content: "\e66e";
}

.van-icon-balance-pay:before {
  content: "\e66f";
}

.van-icon-balance-o:before {
  content: "\e670";
}

.van-icon-bar-chart-o:before {
  content: "\e671";
}

.van-icon-bars:before {
  content: "\e672";
}

.van-icon-balance-list-o:before {
  content: "\e673";
}

.van-icon-birthday-cake-o:before {
  content: "\e674";
}

.van-icon-bookmark:before {
  content: "\e675";
}

.van-icon-bill:before {
  content: "\e676";
}

.van-icon-bell:before {
  content: "\e677";
}

.van-icon-browsing-history-o:before {
  content: "\e678";
}

.van-icon-browsing-history:before {
  content: "\e679";
}

.van-icon-bookmark-o:before {
  content: "\e67a";
}

.van-icon-bulb-o:before {
  content: "\e67b";
}

.van-icon-bullhorn-o:before {
  content: "\e67c";
}

.van-icon-bill-o:before {
  content: "\e67d";
}

.van-icon-calendar-o:before {
  content: "\e67e";
}

.van-icon-brush-o:before {
  content: "\e67f";
}

.van-icon-card:before {
  content: "\e680";
}

.van-icon-cart-o:before {
  content: "\e681";
}

.van-icon-cart-circle:before {
  content: "\e682";
}

.van-icon-cart-circle-o:before {
  content: "\e683";
}

.van-icon-cart:before {
  content: "\e684";
}

.van-icon-cash-on-deliver:before {
  content: "\e685";
}

.van-icon-cash-back-record:before {
  content: "\e686";
}

.van-icon-cashier-o:before {
  content: "\e687";
}

.van-icon-chart-trending-o:before {
  content: "\e688";
}

.van-icon-certificate:before {
  content: "\e689";
}

.van-icon-chat:before {
  content: "\e68a";
}

.van-icon-clear:before {
  content: "\e68b";
}

.van-icon-chat-o:before {
  content: "\e68c";
}

.van-icon-checked:before {
  content: "\e68d";
}

.van-icon-clock:before {
  content: "\e68e";
}

.van-icon-clock-o:before {
  content: "\e68f";
}

.van-icon-close:before {
  content: "\e690";
}

.van-icon-closed-eye:before {
  content: "\e691";
}

.van-icon-circle:before {
  content: "\e692";
}

.van-icon-cluster-o:before {
  content: "\e693";
}

.van-icon-column:before {
  content: "\e694";
}

.van-icon-comment-circle-o:before {
  content: "\e695";
}

.van-icon-cluster:before {
  content: "\e696";
}

.van-icon-comment:before {
  content: "\e697";
}

.van-icon-comment-o:before {
  content: "\e698";
}

.van-icon-comment-circle:before {
  content: "\e699";
}

.van-icon-completed:before {
  content: "\e69a";
}

.van-icon-credit-pay:before {
  content: "\e69b";
}

.van-icon-coupon:before {
  content: "\e69c";
}

.van-icon-debit-pay:before {
  content: "\e69d";
}

.van-icon-coupon-o:before {
  content: "\e69e";
}

.van-icon-contact:before {
  content: "\e69f";
}

.van-icon-descending:before {
  content: "\e6a0";
}

.van-icon-desktop-o:before {
  content: "\e6a1";
}

.van-icon-diamond-o:before {
  content: "\e6a2";
}

.van-icon-description:before {
  content: "\e6a3";
}

.van-icon-delete:before {
  content: "\e6a4";
}

.van-icon-diamond:before {
  content: "\e6a5";
}

.van-icon-delete-o:before {
  content: "\e6a6";
}

.van-icon-cross:before {
  content: "\e6a7";
}

.van-icon-edit:before {
  content: "\e6a8";
}

.van-icon-ellipsis:before {
  content: "\e6a9";
}

.van-icon-down:before {
  content: "\e6aa";
}

.van-icon-discount:before {
  content: "\e6ab";
}

.van-icon-ecard-pay:before {
  content: "\e6ac";
}

.van-icon-envelop-o:before {
  content: "\e6ae";
}

.van-icon-shield-o:before {
  content: "\e74b";
}

.van-icon-guide-o:before {
  content: "\e74c";
}

.van-icon-cash-o:before {
  content: "\e74d";
}

.van-icon-qq:before {
  content: "\e74e";
}

.van-icon-wechat-moments:before {
  content: "\e74f";
}

.van-icon-weibo:before {
  content: "\e750";
}

.van-icon-link-o:before {
  content: "\e751";
}

.van-icon-miniprogram-o:before {
  content: "\e752";
}

/* stylelint-disable selector-pseudo-element-colon-notation */
/* stylelint-disable font-family-no-missing-generic-family-keyword */
@font-face {
  font-weight: normal;
  font-family: "vant-icon";
  font-style: normal;
  font-display: auto;
  src: url("data:font/woff2;charset=utf-8;base64,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")
      format("woff2"),
    url("//at.alicdn.com/t/font_2553510_iv4v8nulyz.woff?t=1649083952952")
      format("woff"),
    url("//at.alicdn.com/t/font_2553510_iv4v8nulyz.ttf?t=1649083952952")
      format("truetype");
}

.van-icon__image {
  display: block;
  width: 1em;
  height: 1em;
  object-fit: contain;
}

.van-tabbar-item {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  color: #646566;
  font-size: 12px;
  line-height: 1;
  cursor: pointer;
}

.van-tabbar-item__icon {
  position: relative;
  margin-bottom: 4px;
  font-size: 22px;
}

.van-tabbar-item__icon .van-icon {
  display: block;
}

.van-tabbar-item__icon img {
  display: block;
  height: 20px;
}

.van-tabbar-item--active {
  color: #1989fa;
  background-color: #fff;
}

.van-tabbar-item .van-info {
  margin-top: 4px;
}

.van-step {
  position: relative;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  color: #969799;
  font-size: 14px;
}

.van-step__circle {
  display: block;
  width: 5px;
  height: 5px;
  background-color: #969799;
  border-radius: 50%;
}

.van-step__line {
  position: absolute;
  background-color: #ebedf0;
  -webkit-transition: background-color 0.3s;
  transition: background-color 0.3s;
}

.van-step--horizontal {
  float: left;
}

.van-step--horizontal:first-child .van-step__title {
  margin-left: 0;
  -webkit-transform: none;
  transform: none;
}

.van-step--horizontal:last-child {
  position: absolute;
  right: 1px;
  width: auto;
}

.van-step--horizontal:last-child .van-step__title {
  margin-left: 0;
  -webkit-transform: none;
  transform: none;
}

.van-step--horizontal:last-child .van-step__circle-container {
  right: -9px;
  left: auto;
}

.van-step--horizontal .van-step__circle-container {
  position: absolute;
  top: 30px;
  left: -8px;
  z-index: 1;
  padding: 0 8px;
  background-color: #fff;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
}

.van-step--horizontal .van-step__title {
  display: inline-block;
  margin-left: 3px;
  font-size: 12px;
  -webkit-transform: translateX(-50%);
  transform: translateX(-50%);
}

@media (max-width: 321px) {
  .van-step--horizontal .van-step__title {
    font-size: 11px;
  }
}

.van-step--horizontal .van-step__line {
  top: 30px;
  left: 0;
  width: 100%;
  height: 1px;
}

.van-step--horizontal .van-step__icon {
  display: block;
  font-size: 12px;
}

.van-step--horizontal .van-step--process {
  color: #323233;
}

.van-step--vertical {
  display: block;
  float: none;
  padding: 10px 10px 10px 0;
  line-height: 18px;
}

.van-step--vertical:not(:last-child)::after {
  border-bottom-width: 1px;
}

.van-step--vertical .van-step__circle-container {
  position: absolute;
  top: 19px;
  left: -15px;
  z-index: 1;
  font-size: 12px;
  line-height: 1;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}

.van-step--vertical .van-step__line {
  top: 16px;
  left: -15px;
  width: 1px;
  height: 100%;
}

.van-step:last-child .van-step__line {
  width: 0;
}

.van-step--finish {
  color: #323233;
}

.van-step--finish .van-step__circle,
.van-step--finish .van-step__line {
  background-color: #07c160;
}

.van-step__icon,
.van-step__title {
  -webkit-transition: color 0.3s;
  transition: color 0.3s;
}

.van-step__icon--active,
.van-step__title--active,
.van-step__icon--finish,
.van-step__title--finish {
  color: #07c160;
}

.van-rate {
  display: -webkit-inline-box;
  display: -webkit-inline-flex;
  display: inline-flex;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}

.van-rate__item {
  position: relative;
}

.van-rate__item:not(:last-child) {
  padding-right: 4px;
}

.van-rate__icon {
  display: block;
  width: 1em;
  color: #c8c9cc;
  font-size: 20px;
}

.van-rate__icon--half {
  position: absolute;
  top: 0;
  left: 0;
  width: 0.5em;
  overflow: hidden;
}

.van-rate__icon--full {
  color: #ee0a24;
}

.van-rate__icon--disabled {
  color: #c8c9cc;
}

.van-rate--disabled {
  cursor: not-allowed;
}

.van-rate--readonly {
  cursor: default;
}

.van-notice-bar {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  height: 40px;
  padding: 0 16px;
  color: #ed6a0c;
  font-size: 14px;
  line-height: 24px;
  background-color: #fffbe8;
}

.van-notice-bar__left-icon,
.van-notice-bar__right-icon {
  min-width: 24px;
  font-size: 16px;
}

.van-notice-bar__right-icon {
  text-align: right;
  cursor: pointer;
}

.van-notice-bar__wrap {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  height: 100%;
  overflow: hidden;
}

.van-notice-bar__content {
  position: absolute;
  white-space: nowrap;
  -webkit-transition-timing-function: linear;
  transition-timing-function: linear;
}

.van-notice-bar__content.van-ellipsis {
  max-width: 100%;
}

.van-notice-bar--wrapable {
  height: auto;
  padding: 8px 16px;
}

.van-notice-bar--wrapable .van-notice-bar__wrap {
  height: auto;
}

.van-notice-bar--wrapable .van-notice-bar__content {
  position: relative;
  white-space: normal;
  word-wrap: break-word;
}

.van-nav-bar {
  position: relative;
  z-index: 1;
  line-height: 22px;
  text-align: center;
  background-color: #fff;
  -webkit-user-select: none;
  user-select: none;
}

.van-nav-bar--fixed {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
}

.van-nav-bar--safe-area-inset-top {
  padding-top: constant(safe-area-inset-top);
  padding-top: env(safe-area-inset-top);
}

.van-nav-bar .van-icon {
  color: #1989fa;
}

.van-nav-bar__content {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  height: 46px;
}

.van-nav-bar__arrow {
  margin-right: 4px;
  font-size: 16px;
}

.van-nav-bar__title {
  max-width: 60%;
  margin: 0 auto;
  color: #323233;
  font-weight: 500;
  font-size: 16px;
}

.van-nav-bar__left,
.van-nav-bar__right {
  position: absolute;
  top: 0;
  bottom: 0;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  padding: 0 16px;
  font-size: 14px;
  cursor: pointer;
}

.van-nav-bar__left:active,
.van-nav-bar__right:active {
  opacity: 0.7;
}

.van-nav-bar__left {
  left: 0;
}

.van-nav-bar__right {
  right: 0;
}

.van-nav-bar__text {
  color: #1989fa;
}

.van-grid-item {
  position: relative;
  box-sizing: border-box;
}

.van-grid-item--square {
  height: 0;
}

.van-grid-item__icon {
  font-size: 28px;
}

.van-grid-item__icon-wrapper {
  position: relative;
}

.van-grid-item__text {
  color: #646566;
  font-size: 12px;
  line-height: 1.5;
  word-break: break-all;
}

.van-grid-item__icon + .van-grid-item__text {
  margin-top: 8px;
}

.van-grid-item__content {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  box-sizing: border-box;
  height: 100%;
  padding: 16px 8px;
  background-color: #fff;
}

.van-grid-item__content::after {
  z-index: 1;
  border-width: 0 1px 1px 0;
}

.van-grid-item__content--square {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
}

.van-grid-item__content--center {
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
}

.van-grid-item__content--horizontal {
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  -webkit-flex-direction: row;
  flex-direction: row;
}

.van-grid-item__content--horizontal
  .van-grid-item__icon
  + .van-grid-item__text {
  margin-top: 0;
  margin-left: 8px;
}

.van-grid-item__content--surround::after {
  border-width: 1px;
}

.van-grid-item__content--clickable {
  cursor: pointer;
}

.van-grid-item__content--clickable:active {
  background-color: #f2f3f5;
}

.van-goods-action-icon {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  min-width: 48px;
  height: 100%;
  color: #646566;
  font-size: 10px;
  line-height: 1;
  text-align: center;
  background-color: #fff;
  cursor: pointer;
}

.van-goods-action-icon:active {
  background-color: #f2f3f5;
}

.van-goods-action-icon__icon {
  position: relative;
  width: 1em;
  margin: 0 auto 5px;
  color: #323233;
  font-size: 18px;
}

.van-checkbox {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  overflow: hidden;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}

.van-checkbox--disabled {
  cursor: not-allowed;
}

.van-checkbox--label-disabled {
  cursor: default;
}

.van-checkbox--horizontal {
  margin-right: 12px;
}

.van-checkbox__icon {
  -webkit-box-flex: 0;
  -webkit-flex: none;
  flex: none;
  height: 1em;
  font-size: 20px;
  line-height: 1em;
  cursor: pointer;
}

.van-checkbox__icon .van-icon {
  display: block;
  box-sizing: border-box;
  width: 1.25em;
  height: 1.25em;
  color: transparent;
  font-size: 0.8em;
  line-height: 1.25;
  text-align: center;
  border: 1px solid #c8c9cc;
  -webkit-transition-duration: 0.2s;
  transition-duration: 0.2s;
  -webkit-transition-property: color, border-color, background-color;
  transition-property: color, border-color, background-color;
}

.van-checkbox__icon--round .van-icon {
  border-radius: 100%;
}

.van-checkbox__icon--checked .van-icon {
  color: #fff;
  background-color: #1989fa;
  border-color: #1989fa;
}

.van-checkbox__icon--disabled {
  cursor: not-allowed;
}

.van-checkbox__icon--disabled .van-icon {
  background-color: #ebedf0;
  border-color: #c8c9cc;
}

.van-checkbox__icon--disabled.van-checkbox__icon--checked .van-icon {
  color: #c8c9cc;
}

.van-checkbox__label {
  margin-left: 8px;
  color: #323233;
  line-height: 20px;
}

.van-checkbox__label--left {
  margin: 0 8px 0 0;
}

.van-checkbox__label--disabled {
  color: #c8c9cc;
}

.van-coupon {
  margin: 0 12px 12px;
  overflow: hidden;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
}

.van-coupon:active {
  background-color: #f2f3f5;
}

.van-coupon__content {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  box-sizing: border-box;
  min-height: 84px;
  padding: 14px 0;
  color: #323233;
}

.van-coupon__head {
  position: relative;
  min-width: 96px;
  padding: 0 8px;
  color: #ee0a24;
  text-align: center;
}

.van-coupon__amount,
.van-coupon__condition,
.van-coupon__name,
.van-coupon__valid {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.van-coupon__amount {
  margin-bottom: 6px;
  font-weight: 500;
  font-size: 30px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.van-coupon__amount span {
  font-weight: normal;
  font-size: 40%;
}

.van-coupon__amount span:not(:empty) {
  margin-left: 2px;
}

.van-coupon__condition {
  font-size: 12px;
  line-height: 16px;
  white-space: pre-wrap;
}

.van-coupon__body {
  position: relative;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  border-radius: 0 8px 8px 0;
}

.van-coupon__name {
  margin-bottom: 10px;
  font-weight: bold;
  font-size: 14px;
  line-height: 20px;
}

.van-coupon__valid {
  font-size: 12px;
}

.van-coupon__corner {
  position: absolute;
  top: 0;
  right: 16px;
  bottom: 0;
}

.van-coupon__description {
  padding: 8px 16px;
  font-size: 12px;
  border-top: 1px dashed #ebedf0;
}

.van-coupon--disabled:active {
  background-color: #fff;
}

.van-coupon--disabled .van-coupon-item__content {
  height: 74px;
}

.van-coupon--disabled .van-coupon__head {
  color: inherit;
}

.van-image {
  position: relative;
  display: inline-block;
}

.van-image--round {
  overflow: hidden;
  border-radius: 50%;
}

.van-image--round img {
  border-radius: inherit;
}

.van-image__img,
.van-image__error,
.van-image__loading {
  display: block;
  width: 100%;
  height: 100%;
}

.van-image__error,
.van-image__loading {
  position: absolute;
  top: 0;
  left: 0;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  color: #969799;
  font-size: 14px;
  background-color: #f7f8fa;
}

.van-image__loading-icon {
  color: #dcdee0;
  font-size: 32px;
}

.van-image__error-icon {
  color: #dcdee0;
  font-size: 32px;
}

.van-radio {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  overflow: hidden;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}

.van-radio--disabled {
  cursor: not-allowed;
}

.van-radio--label-disabled {
  cursor: default;
}

.van-radio--horizontal {
  margin-right: 12px;
}

.van-radio__icon {
  -webkit-box-flex: 0;
  -webkit-flex: none;
  flex: none;
  height: 1em;
  font-size: 20px;
  line-height: 1em;
  cursor: pointer;
}

.van-radio__icon .van-icon {
  display: block;
  box-sizing: border-box;
  width: 1.25em;
  height: 1.25em;
  color: transparent;
  font-size: 0.8em;
  line-height: 1.25;
  text-align: center;
  border: 1px solid #c8c9cc;
  -webkit-transition-duration: 0.2s;
  transition-duration: 0.2s;
  -webkit-transition-property: color, border-color, background-color;
  transition-property: color, border-color, background-color;
}

.van-radio__icon--round .van-icon {
  border-radius: 100%;
}

.van-radio__icon--checked .van-icon {
  color: #fff;
  background-color: #1989fa;
  border-color: #1989fa;
}

.van-radio__icon--disabled {
  cursor: not-allowed;
}

.van-radio__icon--disabled .van-icon {
  background-color: #ebedf0;
  border-color: #c8c9cc;
}

.van-radio__icon--disabled.van-radio__icon--checked .van-icon {
  color: #c8c9cc;
}

.van-radio__label {
  margin-left: 8px;
  color: #323233;
  line-height: 20px;
}

.van-radio__label--left {
  margin: 0 8px 0 0;
}

.van-radio__label--disabled {
  color: #c8c9cc;
}

.van-tag {
  position: relative;
  display: -webkit-inline-box;
  display: -webkit-inline-flex;
  display: inline-flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  padding: 0 4px;
  color: #fff;
  font-size: 12px;
  line-height: 16px;
  border-radius: 2px;
}

.van-tag--default {
  background-color: #969799;
}

.van-tag--default.van-tag--plain {
  color: #969799;
}

.van-tag--danger {
  background-color: #ee0a24;
}

.van-tag--danger.van-tag--plain {
  color: #ee0a24;
}

.van-tag--primary {
  background-color: #1989fa;
}

.van-tag--primary.van-tag--plain {
  color: #1989fa;
}

.van-tag--success {
  background-color: #07c160;
}

.van-tag--success.van-tag--plain {
  color: #07c160;
}

.van-tag--warning {
  background-color: #ff976a;
}

.van-tag--warning.van-tag--plain {
  color: #ff976a;
}

.van-tag--plain {
  background-color: #fff;
  border-color: currentColor;
}

.van-tag--plain::before {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  border: 1px solid;
  border-color: inherit;
  border-radius: inherit;
  content: "";
  pointer-events: none;
}

.van-tag--medium {
  padding: 2px 6px;
}

.van-tag--large {
  padding: 4px 8px;
  font-size: 14px;
  border-radius: 4px;
}

.van-tag--mark {
  border-radius: 0 999px 999px 0;
}

.van-tag--mark::after {
  display: block;
  width: 2px;
  content: "";
}

.van-tag--round {
  border-radius: 999px;
}

.van-tag__close {
  margin-left: 2px;
  cursor: pointer;
}

.van-card {
  position: relative;
  box-sizing: border-box;
  padding: 8px 16px;
  color: #323233;
  font-size: 12px;
  background-color: #fafafa;
}

.van-card:not(:first-child) {
  margin-top: 8px;
}

.van-card__header {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
}

.van-card__thumb {
  position: relative;
  -webkit-box-flex: 0;
  -webkit-flex: none;
  flex: none;
  width: 88px;
  height: 88px;
  margin-right: 8px;
}

.van-card__thumb img {
  border-radius: 8px;
}

.van-card__content {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: justify;
  -webkit-justify-content: space-between;
  justify-content: space-between;
  min-width: 0;
  /* hack for flex box ellipsis */
  min-height: 88px;
}

.van-card__content--centered {
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
}

.van-card__title,
.van-card__desc {
  word-wrap: break-word;
}

.van-card__title {
  max-height: 32px;
  font-weight: 500;
  line-height: 16px;
}

.van-card__desc {
  max-height: 20px;
  color: #646566;
  line-height: 20px;
}

.van-card__bottom {
  line-height: 20px;
}

.van-card__price {
  display: inline-block;
  color: #323233;
  font-weight: 500;
  font-size: 12px;
}

.van-card__price-integer {
  font-size: 16px;
  font-family: Avenir-Heavy, PingFang SC, Helvetica Neue, Arial, sans-serif;
}

.van-card__price-decimal {
  font-family: Avenir-Heavy, PingFang SC, Helvetica Neue, Arial, sans-serif;
}

.van-card__origin-price {
  display: inline-block;
  margin-left: 5px;
  color: #969799;
  font-size: 10px;
  text-decoration: line-through;
}

.van-card__num {
  float: right;
  color: #969799;
}

.van-card__tag {
  position: absolute;
  top: 2px;
  left: 0;
}

.van-card__footer {
  -webkit-box-flex: 0;
  -webkit-flex: none;
  flex: none;
  text-align: right;
}

.van-card__footer .van-button {
  margin-left: 5px;
}

.van-cell {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  box-sizing: border-box;
  width: 100%;
  padding: 10px 16px;
  overflow: hidden;
  color: #323233;
  font-size: 14px;
  line-height: 24px;
  background-color: #fff;
}

.van-cell::after {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  right: 16px;
  bottom: 0;
  left: 16px;
  border-bottom: 1px solid #ebedf0;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}

.van-cell:last-child::after,
.van-cell--borderless::after {
  display: none;
}

.van-cell__label {
  margin-top: 4px;
  color: #969799;
  font-size: 12px;
  line-height: 18px;
}

.van-cell__title,
.van-cell__value {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
}

.van-cell__value {
  position: relative;
  overflow: hidden;
  color: #969799;
  text-align: right;
  vertical-align: middle;
  word-wrap: break-word;
}

.van-cell__value--alone {
  color: #323233;
  text-align: left;
}

.van-cell__left-icon,
.van-cell__right-icon {
  height: 24px;
  font-size: 16px;
  line-height: 24px;
}

.van-cell__left-icon {
  margin-right: 4px;
}

.van-cell__right-icon {
  margin-left: 4px;
  color: #969799;
}

.van-cell--clickable {
  cursor: pointer;
}

.van-cell--clickable:active {
  background-color: #f2f3f5;
}

.van-cell--required {
  overflow: visible;
}

.van-cell--required::before {
  position: absolute;
  left: 8px;
  color: #ee0a24;
  font-size: 14px;
  content: "*";
}

.van-cell--center {
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
}

.van-cell--large {
  padding-top: 12px;
  padding-bottom: 12px;
}

.van-cell--large .van-cell__title {
  font-size: 16px;
}

.van-cell--large .van-cell__label {
  font-size: 14px;
}

.van-coupon-cell__value--selected {
  color: #323233;
}

.van-contact-card {
  padding: 16px;
}

.van-contact-card__value {
  margin-left: 5px;
  line-height: 20px;
}

.van-contact-card--add .van-contact-card__value {
  line-height: 40px;
}

.van-contact-card--add .van-cell__left-icon {
  color: #1989fa;
  font-size: 40px;
}

.van-contact-card::before {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  height: 2px;
  background: -webkit-repeating-linear-gradient(
    135deg,
    #ff6c6c 0,
    #ff6c6c 20%,
    transparent 0,
    transparent 25%,
    #1989fa 0,
    #1989fa 45%,
    transparent 0,
    transparent 50%
  );
  background: repeating-linear-gradient(
    -45deg,
    #ff6c6c 0,
    #ff6c6c 20%,
    transparent 0,
    transparent 25%,
    #1989fa 0,
    #1989fa 45%,
    transparent 0,
    transparent 50%
  );
  background-size: 80px;
  content: "";
}

.van-collapse-item {
  position: relative;
}

.van-collapse-item--border::after {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  top: 0;
  right: 16px;
  left: 16px;
  border-top: 1px solid #ebedf0;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}

.van-collapse-item__title .van-cell__right-icon::before {
  -webkit-transform: rotate(90deg) translateZ(0);
  transform: rotate(90deg) translateZ(0);
  -webkit-transition: -webkit-transform 0.3s;
  transition: -webkit-transform 0.3s;
  transition: transform 0.3s;
  transition: transform 0.3s, -webkit-transform 0.3s;
}

.van-collapse-item__title::after {
  right: 16px;
  display: none;
}

.van-collapse-item__title--expanded .van-cell__right-icon::before {
  -webkit-transform: rotate(-90deg);
  transform: rotate(-90deg);
}

.van-collapse-item__title--expanded::after {
  display: block;
}

.van-collapse-item__title--borderless::after {
  display: none;
}

.van-collapse-item__title--disabled {
  cursor: not-allowed;
}

.van-collapse-item__title--disabled,
.van-collapse-item__title--disabled .van-cell__right-icon {
  color: #c8c9cc;
}

.van-collapse-item__title--disabled:active {
  background-color: #fff;
}

.van-collapse-item__wrapper {
  overflow: hidden;
  -webkit-transition: height 0.3s ease-in-out;
  transition: height 0.3s ease-in-out;
  will-change: height;
}

.van-collapse-item__content {
  padding: 12px 16px;
  color: #969799;
  font-size: 14px;
  line-height: 1.5;
  background-color: #fff;
}

.van-field__label {
  -webkit-box-flex: 0;
  -webkit-flex: none;
  flex: none;
  box-sizing: border-box;
  width: 6.2em;
  margin-right: 12px;
  color: #646566;
  text-align: left;
  word-wrap: break-word;
}

.van-field__label--center {
  text-align: center;
}

.van-field__label--right {
  text-align: right;
}

.van-field--disabled .van-field__label {
  color: #c8c9cc;
}

.van-field__value {
  overflow: visible;
}

.van-field__body {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
}

.van-field__control {
  display: block;
  box-sizing: border-box;
  width: 100%;
  min-width: 0;
  margin: 0;
  padding: 0;
  color: #323233;
  line-height: inherit;
  text-align: left;
  background-color: transparent;
  border: 0;
  resize: none;
}

.van-field__control::-webkit-input-placeholder {
  color: #c8c9cc;
}

.van-field__control::placeholder {
  color: #c8c9cc;
}

.van-field__control:disabled {
  color: #c8c9cc;
  cursor: not-allowed;
  opacity: 1;
  -webkit-text-fill-color: #c8c9cc;
}

.van-field__control:read-only {
  cursor: default;
}

.van-field__control--center {
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  text-align: center;
}

.van-field__control--right {
  -webkit-box-pack: end;
  -webkit-justify-content: flex-end;
  justify-content: flex-end;
  text-align: right;
}

.van-field__control--custom {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  min-height: 24px;
}

.van-field__control[type="date"],
.van-field__control[type="time"],
.van-field__control[type="datetime-local"] {
  min-height: 24px;
}

.van-field__control[type="search"] {
  -webkit-appearance: none;
}

.van-field__clear,
.van-field__icon,
.van-field__button,
.van-field__right-icon {
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
}

.van-field__clear,
.van-field__right-icon {
  margin-right: -8px;
  padding: 0 8px;
  line-height: inherit;
}

.van-field__clear {
  color: #c8c9cc;
  font-size: 16px;
  cursor: pointer;
}

.van-field__left-icon .van-icon,
.van-field__right-icon .van-icon {
  display: block;
  font-size: 16px;
  line-height: inherit;
}

.van-field__left-icon {
  margin-right: 4px;
}

.van-field__right-icon {
  color: #969799;
}

.van-field__button {
  padding-left: 8px;
}

.van-field__error-message {
  color: #ee0a24;
  font-size: 12px;
  text-align: left;
}

.van-field__error-message--center {
  text-align: center;
}

.van-field__error-message--right {
  text-align: right;
}

.van-field__word-limit {
  margin-top: 4px;
  color: #646566;
  font-size: 12px;
  line-height: 16px;
  text-align: right;
}

.van-field--error .van-field__control::-webkit-input-placeholder {
  color: #ee0a24;
  -webkit-text-fill-color: currentColor;
}

.van-field--error .van-field__control,
.van-field--error .van-field__control::placeholder {
  color: #ee0a24;
  -webkit-text-fill-color: currentColor;
}

.van-field--min-height .van-field__control {
  min-height: 60px;
}

.van-search {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  box-sizing: border-box;
  padding: 10px 12px;
  background-color: #fff;
}

.van-search__content {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  padding-left: 12px;
  background-color: #f7f8fa;
  border-radius: 2px;
}

.van-search__content--round {
  border-radius: 999px;
}

.van-search__label {
  padding: 0 5px;
  color: #323233;
  font-size: 14px;
  line-height: 34px;
}

.van-search .van-cell {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  padding: 5px 8px 5px 0;
  background-color: transparent;
}

.van-search .van-cell .van-field__left-icon {
  color: #969799;
}

.van-search--show-action {
  padding-right: 0;
}

.van-search input::-webkit-search-decoration,
.van-search input::-webkit-search-cancel-button,
.van-search input::-webkit-search-results-button,
.van-search input::-webkit-search-results-decoration {
  display: none;
}

.van-search__action {
  padding: 0 8px;
  color: #323233;
  font-size: 14px;
  line-height: 34px;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}

.van-search__action:active {
  background-color: #f2f3f5;
}

.van-overflow-hidden {
  overflow: hidden !important;
}

.van-popup {
  position: fixed;
  max-height: 100%;
  overflow-y: auto;
  background-color: #fff;
  -webkit-transition: -webkit-transform 0.3s;
  transition: -webkit-transform 0.3s;
  transition: transform 0.3s;
  transition: transform 0.3s, -webkit-transform 0.3s;
  -webkit-overflow-scrolling: touch;
}

.van-popup--center {
  top: 50%;
  left: 50%;
  -webkit-transform: translate3d(-50%, -50%, 0);
  transform: translate3d(-50%, -50%, 0);
}

.van-popup--center.van-popup--round {
  border-radius: 16px;
}

.van-popup--top {
  top: 0;
  left: 0;
  width: 100%;
}

.van-popup--top.van-popup--round {
  border-radius: 0 0 16px 16px;
}

.van-popup--right {
  top: 50%;
  right: 0;
  -webkit-transform: translate3d(0, -50%, 0);
  transform: translate3d(0, -50%, 0);
}

.van-popup--right.van-popup--round {
  border-radius: 16px 0 0 16px;
}

.van-popup--bottom {
  bottom: 0;
  left: 0;
  width: 100%;
}

.van-popup--bottom.van-popup--round {
  border-radius: 16px 16px 0 0;
}

.van-popup--left {
  top: 50%;
  left: 0;
  -webkit-transform: translate3d(0, -50%, 0);
  transform: translate3d(0, -50%, 0);
}

.van-popup--left.van-popup--round {
  border-radius: 0 16px 16px 0;
}

.van-popup--safe-area-inset-bottom {
  padding-bottom: constant(safe-area-inset-bottom);
  padding-bottom: env(safe-area-inset-bottom);
}

.van-popup-slide-top-enter-active,
.van-popup-slide-left-enter-active,
.van-popup-slide-right-enter-active,
.van-popup-slide-bottom-enter-active {
  -webkit-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
}

.van-popup-slide-top-leave-active,
.van-popup-slide-left-leave-active,
.van-popup-slide-right-leave-active,
.van-popup-slide-bottom-leave-active {
  -webkit-transition-timing-function: ease-in;
  transition-timing-function: ease-in;
}

.van-popup-slide-top-enter,
.van-popup-slide-top-leave-active {
  -webkit-transform: translate3d(0, -100%, 0);
  transform: translate3d(0, -100%, 0);
}

.van-popup-slide-right-enter,
.van-popup-slide-right-leave-active {
  -webkit-transform: translate3d(100%, -50%, 0);
  transform: translate3d(100%, -50%, 0);
}

.van-popup-slide-bottom-enter,
.van-popup-slide-bottom-leave-active {
  -webkit-transform: translate3d(0, 100%, 0);
  transform: translate3d(0, 100%, 0);
}

.van-popup-slide-left-enter,
.van-popup-slide-left-leave-active {
  -webkit-transform: translate3d(-100%, -50%, 0);
  transform: translate3d(-100%, -50%, 0);
}

.van-popup__close-icon {
  position: absolute;
  z-index: 1;
  color: #c8c9cc;
  font-size: 22px;
  cursor: pointer;
}

.van-popup__close-icon:active {
  color: #969799;
}

.van-popup__close-icon--top-left {
  top: 16px;
  left: 16px;
}

.van-popup__close-icon--top-right {
  top: 16px;
  right: 16px;
}

.van-popup__close-icon--bottom-left {
  bottom: 16px;
  left: 16px;
}

.van-popup__close-icon--bottom-right {
  right: 16px;
  bottom: 16px;
}

.van-share-sheet__header {
  padding: 12px 16px 4px;
  text-align: center;
}

.van-share-sheet__title {
  margin-top: 8px;
  color: #323233;
  font-weight: normal;
  font-size: 14px;
  line-height: 20px;
}

.van-share-sheet__description {
  display: block;
  margin-top: 8px;
  color: #969799;
  font-size: 12px;
  line-height: 16px;
}

.van-share-sheet__options {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  padding: 16px 0 16px 8px;
  overflow-x: auto;
  overflow-y: visible;
  -webkit-overflow-scrolling: touch;
}

.van-share-sheet__options--border::before {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  top: 0;
  right: 0;
  left: 16px;
  border-top: 1px solid #ebedf0;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}

.van-share-sheet__options::-webkit-scrollbar {
  height: 0;
}

.van-share-sheet__option {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}

.van-share-sheet__option:active {
  opacity: 0.7;
}

.van-share-sheet__icon {
  width: 48px;
  height: 48px;
  margin: 0 16px;
}

.van-share-sheet__name {
  margin-top: 8px;
  padding: 0 4px;
  color: #646566;
  font-size: 12px;
}

.van-share-sheet__option-description {
  padding: 0 4px;
  color: #c8c9cc;
  font-size: 12px;
}

.van-share-sheet__cancel {
  display: block;
  width: 100%;
  padding: 0;
  font-size: 16px;
  line-height: 48px;
  text-align: center;
  background: #fff;
  border: none;
  cursor: pointer;
}

.van-share-sheet__cancel::before {
  display: block;
  height: 8px;
  background-color: #f7f8fa;
  content: " ";
}

.van-share-sheet__cancel:active {
  background-color: #f2f3f5;
}

.van-popover {
  position: absolute;
  overflow: visible;
  background-color: transparent;
  -webkit-transition: opacity 0.15s, -webkit-transform 0.15s;
  transition: opacity 0.15s, -webkit-transform 0.15s;
  transition: opacity 0.15s, transform 0.15s;
  transition: opacity 0.15s, transform 0.15s, -webkit-transform 0.15s;
}

.van-popover__wrapper {
  display: inline-block;
}

.van-popover__arrow {
  position: absolute;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 6px;
}

.van-popover__content {
  overflow: hidden;
  border-radius: 8px;
}

.van-popover__action {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  box-sizing: border-box;
  width: 128px;
  height: 44px;
  padding: 0 16px;
  font-size: 14px;
  line-height: 20px;
  cursor: pointer;
}

.van-popover__action:last-child .van-popover__action-text::after {
  display: none;
}

.van-popover__action-text {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  height: 100%;
}

.van-popover__action-icon {
  margin-right: 8px;
  font-size: 20px;
}

.van-popover__action--with-icon .van-popover__action-text {
  -webkit-box-pack: start;
  -webkit-justify-content: flex-start;
  justify-content: flex-start;
}

.van-popover[data-popper-placement^="top"] .van-popover__arrow {
  bottom: 0;
  border-top-color: currentColor;
  border-bottom-width: 0;
  -webkit-transform: translate(-50%, 100%);
  transform: translate(-50%, 100%);
}

.van-popover[data-popper-placement="top"] {
  -webkit-transform-origin: 50% 100%;
  transform-origin: 50% 100%;
}

.van-popover[data-popper-placement="top"] .van-popover__arrow {
  left: 50%;
}

.van-popover[data-popper-placement="top-start"] {
  -webkit-transform-origin: 0 100%;
  transform-origin: 0 100%;
}

.van-popover[data-popper-placement="top-start"] .van-popover__arrow {
  left: 16px;
}

.van-popover[data-popper-placement="top-end"] {
  -webkit-transform-origin: 100% 100%;
  transform-origin: 100% 100%;
}

.van-popover[data-popper-placement="top-end"] .van-popover__arrow {
  right: 16px;
}

.van-popover[data-popper-placement^="left"] .van-popover__arrow {
  right: 0;
  border-right-width: 0;
  border-left-color: currentColor;
  -webkit-transform: translate(100%, -50%);
  transform: translate(100%, -50%);
}

.van-popover[data-popper-placement="left"] {
  -webkit-transform-origin: 100% 50%;
  transform-origin: 100% 50%;
}

.van-popover[data-popper-placement="left"] .van-popover__arrow {
  top: 50%;
}

.van-popover[data-popper-placement="left-start"] {
  -webkit-transform-origin: 100% 0;
  transform-origin: 100% 0;
}

.van-popover[data-popper-placement="left-start"] .van-popover__arrow {
  top: 16px;
}

.van-popover[data-popper-placement="left-end"] {
  -webkit-transform-origin: 100% 100%;
  transform-origin: 100% 100%;
}

.van-popover[data-popper-placement="left-end"] .van-popover__arrow {
  bottom: 16px;
}

.van-popover[data-popper-placement^="right"] .van-popover__arrow {
  left: 0;
  border-right-color: currentColor;
  border-left-width: 0;
  -webkit-transform: translate(-100%, -50%);
  transform: translate(-100%, -50%);
}

.van-popover[data-popper-placement="right"] {
  -webkit-transform-origin: 0 50%;
  transform-origin: 0 50%;
}

.van-popover[data-popper-placement="right"] .van-popover__arrow {
  top: 50%;
}

.van-popover[data-popper-placement="right-start"] {
  -webkit-transform-origin: 0 0;
  transform-origin: 0 0;
}

.van-popover[data-popper-placement="right-start"] .van-popover__arrow {
  top: 16px;
}

.van-popover[data-popper-placement="right-end"] {
  -webkit-transform-origin: 0 100%;
  transform-origin: 0 100%;
}

.van-popover[data-popper-placement="right-end"] .van-popover__arrow {
  bottom: 16px;
}

.van-popover[data-popper-placement^="bottom"] .van-popover__arrow {
  top: 0;
  border-top-width: 0;
  border-bottom-color: currentColor;
  -webkit-transform: translate(-50%, -100%);
  transform: translate(-50%, -100%);
}

.van-popover[data-popper-placement="bottom"] {
  -webkit-transform-origin: 50% 0;
  transform-origin: 50% 0;
}

.van-popover[data-popper-placement="bottom"] .van-popover__arrow {
  left: 50%;
}

.van-popover[data-popper-placement="bottom-start"] {
  -webkit-transform-origin: 0 0;
  transform-origin: 0 0;
}

.van-popover[data-popper-placement="bottom-start"] .van-popover__arrow {
  left: 16px;
}

.van-popover[data-popper-placement="bottom-end"] {
  -webkit-transform-origin: 100% 0;
  transform-origin: 100% 0;
}

.van-popover[data-popper-placement="bottom-end"] .van-popover__arrow {
  right: 16px;
}

.van-popover--light {
  color: #323233;
}

.van-popover--light .van-popover__content {
  background-color: #fff;
  box-shadow: 0 2px 12px rgba(50, 50, 51, 0.12);
}

.van-popover--light .van-popover__arrow {
  color: #fff;
}

.van-popover--light .van-popover__action:active {
  background-color: #f2f3f5;
}

.van-popover--light .van-popover__action--disabled {
  color: #c8c9cc;
  cursor: not-allowed;
}

.van-popover--light .van-popover__action--disabled:active {
  background-color: transparent;
}

.van-popover--dark {
  color: #fff;
}

.van-popover--dark .van-popover__content {
  background-color: #4a4a4a;
}

.van-popover--dark .van-popover__arrow {
  color: #4a4a4a;
}

.van-popover--dark .van-popover__action:active {
  background-color: rgba(0, 0, 0, 0.2);
}

.van-popover--dark .van-popover__action--disabled {
  color: #969799;
}

.van-popover--dark .van-popover__action--disabled:active {
  background-color: transparent;
}

.van-popover--dark .van-popover__action-text::after {
  border-color: #646566;
}

.van-popover-zoom-enter,
.van-popover-zoom-leave-active {
  -webkit-transform: scale(0.8);
  transform: scale(0.8);
  opacity: 0;
}

.van-popover-zoom-enter-active {
  -webkit-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
}

.van-popover-zoom-leave-active {
  -webkit-transition-timing-function: ease-in;
  transition-timing-function: ease-in;
}

.van-notify {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  box-sizing: border-box;
  padding: 8px 16px;
  color: #fff;
  font-size: 14px;
  line-height: 20px;
  white-space: pre-wrap;
  text-align: center;
  word-wrap: break-word;
}

.van-notify--primary {
  background-color: #1989fa;
}

.van-notify--success {
  background-color: #07c160;
}

.van-notify--danger {
  background-color: #ee0a24;
}

.van-notify--warning {
  background-color: #ff976a;
}

.van-dropdown-item {
  position: fixed;
  right: 0;
  left: 0;
  z-index: 10;
  overflow: hidden;
}

.van-dropdown-item__icon {
  display: block;
  line-height: inherit;
}

.van-dropdown-item__option {
  text-align: left;
}

.van-dropdown-item__option--active {
  color: #ee0a24;
}

.van-dropdown-item__option--active .van-dropdown-item__icon {
  color: #ee0a24;
}

.van-dropdown-item--up {
  top: 0;
}

.van-dropdown-item--down {
  bottom: 0;
}

.van-dropdown-item__content {
  position: absolute;
  max-height: 80%;
}

.van-loading {
  position: relative;
  color: #c8c9cc;
  font-size: 0;
  vertical-align: middle;
}

.van-loading__spinner {
  position: relative;
  display: inline-block;
  width: 30px;
  max-width: 100%;
  height: 30px;
  max-height: 100%;
  vertical-align: middle;
  -webkit-animation: van-rotate 0.8s linear infinite;
  animation: van-rotate 0.8s linear infinite;
}

.van-loading__spinner--spinner {
  -webkit-animation-timing-function: steps(12);
  animation-timing-function: steps(12);
}

.van-loading__spinner--spinner i {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.van-loading__spinner--spinner i::before {
  display: block;
  width: 2px;
  height: 25%;
  margin: 0 auto;
  background-color: currentColor;
  border-radius: 40%;
  content: " ";
}

.van-loading__spinner--circular {
  -webkit-animation-duration: 2s;
  animation-duration: 2s;
}

.van-loading__circular {
  display: block;
  width: 100%;
  height: 100%;
}

.van-loading__circular circle {
  -webkit-animation: van-circular 1.5s ease-in-out infinite;
  animation: van-circular 1.5s ease-in-out infinite;
  stroke: currentColor;
  stroke-width: 3;
  stroke-linecap: round;
}

.van-loading__text {
  display: inline-block;
  margin-left: 8px;
  color: #969799;
  font-size: 14px;
  vertical-align: middle;
}

.van-loading--vertical {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
}

.van-loading--vertical .van-loading__text {
  margin: 8px 0 0;
}

@-webkit-keyframes van-circular {
  0% {
    stroke-dasharray: 1, 200;
    stroke-dashoffset: 0;
  }

  50% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -40;
  }

  100% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -120;
  }
}

@keyframes van-circular {
  0% {
    stroke-dasharray: 1, 200;
    stroke-dashoffset: 0;
  }

  50% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -40;
  }

  100% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -120;
  }
}

.van-loading__spinner--spinner i:nth-of-type(1) {
  -webkit-transform: rotate(30deg);
  transform: rotate(30deg);
  opacity: 1;
}

.van-loading__spinner--spinner i:nth-of-type(2) {
  -webkit-transform: rotate(60deg);
  transform: rotate(60deg);
  opacity: 0.9375;
}

.van-loading__spinner--spinner i:nth-of-type(3) {
  -webkit-transform: rotate(90deg);
  transform: rotate(90deg);
  opacity: 0.875;
}

.van-loading__spinner--spinner i:nth-of-type(4) {
  -webkit-transform: rotate(120deg);
  transform: rotate(120deg);
  opacity: 0.8125;
}

.van-loading__spinner--spinner i:nth-of-type(5) {
  -webkit-transform: rotate(150deg);
  transform: rotate(150deg);
  opacity: 0.75;
}

.van-loading__spinner--spinner i:nth-of-type(6) {
  -webkit-transform: rotate(180deg);
  transform: rotate(180deg);
  opacity: 0.6875;
}

.van-loading__spinner--spinner i:nth-of-type(7) {
  -webkit-transform: rotate(210deg);
  transform: rotate(210deg);
  opacity: 0.625;
}

.van-loading__spinner--spinner i:nth-of-type(8) {
  -webkit-transform: rotate(240deg);
  transform: rotate(240deg);
  opacity: 0.5625;
}

.van-loading__spinner--spinner i:nth-of-type(9) {
  -webkit-transform: rotate(270deg);
  transform: rotate(270deg);
  opacity: 0.5;
}

.van-loading__spinner--spinner i:nth-of-type(10) {
  -webkit-transform: rotate(300deg);
  transform: rotate(300deg);
  opacity: 0.4375;
}

.van-loading__spinner--spinner i:nth-of-type(11) {
  -webkit-transform: rotate(330deg);
  transform: rotate(330deg);
  opacity: 0.375;
}

.van-loading__spinner--spinner i:nth-of-type(12) {
  -webkit-transform: rotate(360deg);
  transform: rotate(360deg);
  opacity: 0.3125;
}

.van-pull-refresh {
  overflow: hidden;
  -webkit-user-select: none;
  user-select: none;
}

.van-pull-refresh__track {
  position: relative;
  height: 100%;
  -webkit-transition-property: -webkit-transform;
  transition-property: -webkit-transform;
  transition-property: transform;
  transition-property: transform, -webkit-transform;
}

.van-pull-refresh__head {
  position: absolute;
  left: 0;
  width: 100%;
  height: 50px;
  overflow: hidden;
  color: #969799;
  font-size: 14px;
  line-height: 50px;
  text-align: center;
  -webkit-transform: translateY(-100%);
  transform: translateY(-100%);
}

.van-number-keyboard {
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 100;
  width: 100%;
  padding-bottom: 22px;
  background-color: #f2f3f5;
  -webkit-user-select: none;
  user-select: none;
}

.van-number-keyboard--with-title {
  border-radius: 20px 20px 0 0;
}

.van-number-keyboard__header {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  box-sizing: content-box;
  height: 34px;
  padding-top: 6px;
  color: #646566;
  font-size: 16px;
}

.van-number-keyboard__title {
  display: inline-block;
  font-weight: normal;
}

.van-number-keyboard__title-left {
  position: absolute;
  left: 0;
}

.van-number-keyboard__body {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  padding: 6px 0 0 6px;
}

.van-number-keyboard__keys {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 3;
  -webkit-flex: 3;
  flex: 3;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
}

.van-number-keyboard__close {
  position: absolute;
  right: 0;
  height: 100%;
  padding: 0 16px;
  color: #576b95;
  font-size: 14px;
  background-color: transparent;
  border: none;
  cursor: pointer;
}

.van-number-keyboard__close:active {
  opacity: 0.7;
}

.van-number-keyboard__sidebar {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
}

.van-number-keyboard--unfit {
  padding-bottom: 0;
}

.van-key {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  height: 48px;
  font-size: 28px;
  line-height: 1.5;
  background-color: #fff;
  border-radius: 8px;
  cursor: pointer;
}

.van-key--large {
  position: absolute;
  top: 0;
  right: 6px;
  bottom: 6px;
  left: 0;
  height: auto;
}

.van-key--blue,
.van-key--delete {
  font-size: 16px;
}

.van-key--active {
  background-color: #ebedf0;
}

.van-key--blue {
  color: #fff;
  background-color: #1989fa;
}

.van-key--blue.van-key--active {
  background-color: #0570db;
}

.van-key__wrapper {
  position: relative;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  -webkit-flex-basis: 33%;
  flex-basis: 33%;
  box-sizing: border-box;
  padding: 0 6px 6px 0;
}

.van-key__wrapper--wider {
  -webkit-flex-basis: 66%;
  flex-basis: 66%;
}

.van-key__delete-icon {
  width: 32px;
  height: 22px;
}

.van-key__collapse-icon {
  width: 30px;
  height: 24px;
}

.van-key__loading-icon {
  color: #fff;
}

.van-list__loading,
.van-list__finished-text,
.van-list__error-text {
  color: #969799;
  font-size: 14px;
  line-height: 50px;
  text-align: center;
}

.van-list__placeholder {
  height: 0;
  pointer-events: none;
}

.van-switch {
  position: relative;
  display: inline-block;
  box-sizing: content-box;
  width: 2em;
  height: 1em;
  font-size: 30px;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.1);
  border-radius: 1em;
  cursor: pointer;
  -webkit-transition: background-color 0.3s;
  transition: background-color 0.3s;
}

.van-switch__node {
  position: absolute;
  top: 0;
  left: 0;
  width: 1em;
  height: 1em;
  font-size: inherit;
  background-color: #fff;
  border-radius: 100%;
  box-shadow: 0 3px 1px 0 rgba(0, 0, 0, 0.05), 0 2px 2px 0 rgba(0, 0, 0, 0.1),
    0 3px 3px 0 rgba(0, 0, 0, 0.05);
  -webkit-transition: -webkit-transform 0.3s cubic-bezier(0.3, 1.05, 0.4, 1.05);
  transition: -webkit-transform 0.3s cubic-bezier(0.3, 1.05, 0.4, 1.05);
  transition: transform 0.3s cubic-bezier(0.3, 1.05, 0.4, 1.05);
  transition: transform 0.3s cubic-bezier(0.3, 1.05, 0.4, 1.05),
    -webkit-transform 0.3s cubic-bezier(0.3, 1.05, 0.4, 1.05);
}

.van-switch__loading {
  top: 25%;
  left: 25%;
  width: 50%;
  height: 50%;
  line-height: 1;
}

.van-switch--on {
  background-color: #1989fa;
}

.van-switch--on .van-switch__node {
  -webkit-transform: translateX(1em);
  transform: translateX(1em);
}

.van-switch--on .van-switch__loading {
  color: #1989fa;
}

.van-switch--disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

.van-switch--loading {
  cursor: default;
}

.van-switch-cell {
  padding-top: 9px;
  padding-bottom: 9px;
}

.van-switch-cell--large {
  padding-top: 11px;
  padding-bottom: 11px;
}

.van-switch-cell .van-switch {
  float: right;
}

.van-button {
  position: relative;
  display: inline-block;
  box-sizing: border-box;
  height: 44px;
  margin: 0;
  padding: 0;
  font-size: 16px;
  line-height: 1.2;
  text-align: center;
  border-radius: 2px;
  cursor: pointer;
  -webkit-transition: opacity 0.2s;
  transition: opacity 0.2s;
  -webkit-appearance: none;
}

.van-button::before {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 100%;
  height: 100%;
  background-color: #000;
  border: inherit;
  border-color: #000;
  border-radius: inherit;
  /* inherit parent's border radius */
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  opacity: 0;
  content: " ";
}

.van-button:active::before {
  opacity: 0.1;
}

.van-button--loading::before,
.van-button--disabled::before {
  display: none;
}

.van-button--default {
  color: #323233;
  background-color: #fff;
  border: 1px solid #ebedf0;
}

.van-button--primary {
  color: #fff;
  background-color: #07c160;
  border: 1px solid #07c160;
}

.van-button--info {
  color: #fff;
  background-color: #1989fa;
  border: 1px solid #1989fa;
}

.van-button--danger {
  color: #fff;
  background-color: #ee0a24;
  border: 1px solid #ee0a24;
}

.van-button--warning {
  color: #fff;
  background-color: #ff976a;
  border: 1px solid #ff976a;
}

.van-button--plain {
  background-color: #fff;
}

.van-button--plain.van-button--primary {
  color: #07c160;
}

.van-button--plain.van-button--info {
  color: #1989fa;
}

.van-button--plain.van-button--danger {
  color: #ee0a24;
}

.van-button--plain.van-button--warning {
  color: #ff976a;
}

.van-button--large {
  width: 100%;
  height: 50px;
}

.van-button--normal {
  padding: 0 15px;
  font-size: 14px;
}

.van-button--small {
  height: 32px;
  padding: 0 8px;
  font-size: 12px;
}

.van-button__loading {
  color: inherit;
  font-size: inherit;
}

.van-button--mini {
  height: 24px;
  padding: 0 4px;
  font-size: 10px;
}

.van-button--mini + .van-button--mini {
  margin-left: 4px;
}

.van-button--block {
  display: block;
  width: 100%;
}

.van-button--disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

.van-button--loading {
  cursor: default;
}

.van-button--round {
  border-radius: 999px;
}

.van-button--square {
  border-radius: 0;
}

.van-button__content {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  height: 100%;
}

.van-button__content::before {
  content: " ";
}

.van-button__icon {
  font-size: 1.2em;
  line-height: inherit;
}

.van-button__icon + .van-button__text,
.van-button__loading + .van-button__text,
.van-button__text + .van-button__icon,
.van-button__text + .van-button__loading {
  margin-left: 4px;
}

.van-button--hairline {
  border-width: 0;
}

.van-button--hairline::after {
  border-color: inherit;
  border-radius: 4px;
}

.van-button--hairline.van-button--round::after {
  border-radius: 999px;
}

.van-button--hairline.van-button--square::after {
  border-radius: 0;
}

.van-submit-bar {
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 100;
  width: 100%;
  padding-bottom: constant(safe-area-inset-bottom);
  padding-bottom: env(safe-area-inset-bottom);
  background-color: #fff;
  -webkit-user-select: none;
  user-select: none;
}

.van-submit-bar__tip {
  padding: 8px 12px;
  color: #f56723;
  font-size: 12px;
  line-height: 1.5;
  background-color: #fff7cc;
}

.van-submit-bar__tip-icon {
  min-width: 18px;
  font-size: 12px;
  vertical-align: middle;
}

.van-submit-bar__tip-text {
  vertical-align: middle;
}

.van-submit-bar__bar {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: end;
  -webkit-justify-content: flex-end;
  justify-content: flex-end;
  height: 50px;
  padding: 0 16px;
  font-size: 14px;
}

.van-submit-bar__text {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  padding-right: 12px;
  color: #323233;
  text-align: right;
}

.van-submit-bar__text span {
  display: inline-block;
}

.van-submit-bar__suffix-label {
  margin-left: 5px;
  font-weight: 500;
}

.van-submit-bar__price {
  color: #ee0a24;
  font-weight: 500;
  font-size: 12px;
}

.van-submit-bar__price--integer {
  font-size: 20px;
  font-family: Avenir-Heavy, PingFang SC, Helvetica Neue, Arial, sans-serif;
}

.van-submit-bar__button {
  width: 110px;
  height: 40px;
  font-weight: 500;
  border: none;
}

.van-submit-bar__button--danger {
  background: -webkit-linear-gradient(left, #ff6034, #ee0a24);
  background: linear-gradient(to right, #ff6034, #ee0a24);
}

.van-submit-bar--unfit {
  padding-bottom: 0;
}

.van-goods-action-button {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  height: 40px;
  font-weight: 500;
  font-size: 14px;
  border: none;
  border-radius: 0;
}

.van-goods-action-button--first {
  margin-left: 5px;
  border-top-left-radius: 999px;
  border-bottom-left-radius: 999px;
}

.van-goods-action-button--last {
  margin-right: 5px;
  border-top-right-radius: 999px;
  border-bottom-right-radius: 999px;
}

.van-goods-action-button--warning {
  background: -webkit-linear-gradient(left, #ffd01e, #ff8917);
  background: linear-gradient(to right, #ffd01e, #ff8917);
}

.van-goods-action-button--danger {
  background: -webkit-linear-gradient(left, #ff6034, #ee0a24);
  background: linear-gradient(to right, #ff6034, #ee0a24);
}

@media (max-width: 321px) {
  .van-goods-action-button {
    font-size: 13px;
  }
}

.van-toast {
  position: fixed;
  top: 50%;
  left: 50%;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  box-sizing: content-box;
  width: 88px;
  max-width: 70%;
  min-height: 88px;
  padding: 16px;
  color: #fff;
  font-size: 14px;
  line-height: 20px;
  white-space: pre-wrap;
  text-align: center;
  word-break: break-all;
  background-color: rgba(0, 0, 0, 0.7);
  border-radius: 8px;
  -webkit-transform: translate3d(-50%, -50%, 0);
  transform: translate3d(-50%, -50%, 0);
}

.van-toast--unclickable {
  overflow: hidden;
}

.van-toast--unclickable * {
  pointer-events: none;
}

.van-toast--text,
.van-toast--html {
  width: -webkit-fit-content;
  width: fit-content;
  min-width: 96px;
  min-height: 0;
  padding: 8px 12px;
}

.van-toast--text .van-toast__text,
.van-toast--html .van-toast__text {
  margin-top: 0;
}

.van-toast--top {
  top: 20%;
}

.van-toast--bottom {
  top: auto;
  bottom: 20%;
}

.van-toast__icon {
  font-size: 36px;
}

.van-toast__loading {
  padding: 4px;
  color: #fff;
}

.van-toast__text {
  margin-top: 8px;
}

.van-calendar {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  height: 100%;
  background-color: #fff;
}

.van-calendar__popup.van-popup--top,
.van-calendar__popup.van-popup--bottom {
  height: 80%;
}

.van-calendar__popup.van-popup--left,
.van-calendar__popup.van-popup--right {
  height: 100%;
}

.van-calendar__popup .van-popup__close-icon {
  top: 11px;
}

.van-calendar__header {
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  box-shadow: 0 2px 10px rgba(125, 126, 128, 0.16);
}

.van-calendar__month-title,
.van-calendar__header-title,
.van-calendar__header-subtitle {
  height: 44px;
  font-weight: 500;
  line-height: 44px;
  text-align: center;
}

.van-calendar__header-title {
  font-size: 16px;
}

.van-calendar__header-subtitle {
  font-size: 14px;
}

.van-calendar__month-title {
  font-size: 14px;
}

.van-calendar__weekdays {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
}

.van-calendar__weekday {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  font-size: 12px;
  line-height: 30px;
  text-align: center;
}

.van-calendar__body {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  overflow: auto;
  -webkit-overflow-scrolling: touch;
}

.van-calendar__days {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-user-select: none;
  user-select: none;
}

.van-calendar__month-mark {
  position: absolute;
  top: 50%;
  left: 50%;
  z-index: 0;
  color: rgba(242, 243, 245, 0.8);
  font-size: 160px;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  pointer-events: none;
}

.van-calendar__day,
.van-calendar__selected-day {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  text-align: center;
}

.van-calendar__day {
  position: relative;
  width: 14.285%;
  height: 64px;
  font-size: 16px;
  cursor: pointer;
}

.van-calendar__day--end,
.van-calendar__day--start,
.van-calendar__day--start-end,
.van-calendar__day--multiple-middle,
.van-calendar__day--multiple-selected {
  color: #fff;
  background-color: #ee0a24;
}

.van-calendar__day--start {
  border-radius: 4px 0 0 4px;
}

.van-calendar__day--end {
  border-radius: 0 4px 4px 0;
}

.van-calendar__day--start-end,
.van-calendar__day--multiple-selected {
  border-radius: 4px;
}

.van-calendar__day--middle {
  color: #ee0a24;
}

.van-calendar__day--middle::after {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-color: currentColor;
  opacity: 0.1;
  content: "";
}

.van-calendar__day--disabled {
  color: #c8c9cc;
  cursor: default;
}

.van-calendar__top-info,
.van-calendar__bottom-info {
  position: absolute;
  right: 0;
  left: 0;
  font-size: 10px;
  line-height: 14px;
}

@media (max-width: 350px) {
  .van-calendar__top-info,
  .van-calendar__bottom-info {
    font-size: 9px;
  }
}

.van-calendar__top-info {
  top: 6px;
}

.van-calendar__bottom-info {
  bottom: 6px;
}

.van-calendar__selected-day {
  width: 54px;
  height: 54px;
  color: #fff;
  background-color: #ee0a24;
  border-radius: 4px;
}

.van-calendar__footer {
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  padding: 0 16px;
  padding-bottom: constant(safe-area-inset-bottom);
  padding-bottom: env(safe-area-inset-bottom);
}

.van-calendar__footer--unfit {
  padding-bottom: 0;
}

.van-calendar__confirm {
  height: 36px;
  margin: 7px 0;
}

.van-picker {
  position: relative;
  background-color: #fff;
  -webkit-user-select: none;
  user-select: none;
}

.van-picker__toolbar {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: justify;
  -webkit-justify-content: space-between;
  justify-content: space-between;
  height: 44px;
}

.van-picker__cancel,
.van-picker__confirm {
  height: 100%;
  padding: 0 16px;
  font-size: 14px;
  background-color: transparent;
  border: none;
  cursor: pointer;
}

.van-picker__cancel:active,
.van-picker__confirm:active {
  opacity: 0.7;
}

.van-picker__confirm {
  color: #576b95;
}

.van-picker__cancel {
  color: #969799;
}

.van-picker__title {
  max-width: 50%;
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  text-align: center;
}

.van-picker__columns {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  cursor: grab;
}

.van-picker__loading {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 3;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  color: #1989fa;
  background-color: rgba(255, 255, 255, 0.9);
}

.van-picker__frame {
  position: absolute;
  top: 50%;
  right: 16px;
  left: 16px;
  z-index: 2;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
  pointer-events: none;
}

.van-picker__mask {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1;
  width: 100%;
  height: 100%;
  background-image: -webkit-linear-gradient(
      top,
      hsla(0, 0%, 100%, 0.9),
      hsla(0, 0%, 100%, 0.4)
    ),
    -webkit-linear-gradient(bottom, hsla(0, 0%, 100%, 0.9), hsla(0, 0%, 100%, 0.4));
  background-image: linear-gradient(
      180deg,
      hsla(0, 0%, 100%, 0.9),
      hsla(0, 0%, 100%, 0.4)
    ),
    linear-gradient(0deg, hsla(0, 0%, 100%, 0.9), hsla(0, 0%, 100%, 0.4));
  background-repeat: no-repeat;
  background-position: top, bottom;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  pointer-events: none;
}

.van-picker-column {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  overflow: hidden;
  font-size: 16px;
}

.van-picker-column__wrapper {
  -webkit-transition-timing-function: cubic-bezier(0.23, 1, 0.68, 1);
  transition-timing-function: cubic-bezier(0.23, 1, 0.68, 1);
}

.van-picker-column__item {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  padding: 0 4px;
  color: #000;
}

.van-picker-column__item--disabled {
  cursor: not-allowed;
  opacity: 0.3;
}

.van-action-sheet {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  max-height: 80%;
  overflow: hidden;
  color: #323233;
}

.van-action-sheet__content {
  -webkit-box-flex: 1;
  -webkit-flex: 1 auto;
  flex: 1 auto;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

.van-action-sheet__item,
.van-action-sheet__cancel {
  display: block;
  width: 100%;
  padding: 14px 16px;
  font-size: 16px;
  background-color: #fff;
  border: none;
  cursor: pointer;
}

.van-action-sheet__item:active,
.van-action-sheet__cancel:active {
  background-color: #f2f3f5;
}

.van-action-sheet__item {
  line-height: 22px;
}

.van-action-sheet__item--loading,
.van-action-sheet__item--disabled {
  color: #c8c9cc;
}

.van-action-sheet__item--loading:active,
.van-action-sheet__item--disabled:active {
  background-color: #fff;
}

.van-action-sheet__item--disabled {
  cursor: not-allowed;
}

.van-action-sheet__item--loading {
  cursor: default;
}

.van-action-sheet__cancel {
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  box-sizing: border-box;
  color: #646566;
}

.van-action-sheet__subname {
  margin-top: 8px;
  color: #969799;
  font-size: 12px;
  line-height: 18px;
}

.van-action-sheet__gap {
  display: block;
  height: 8px;
  background-color: #f7f8fa;
}

.van-action-sheet__header {
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  font-weight: 500;
  font-size: 16px;
  line-height: 48px;
  text-align: center;
}

.van-action-sheet__description {
  position: relative;
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  padding: 20px 16px;
  color: #969799;
  font-size: 14px;
  line-height: 20px;
  text-align: center;
}

.van-action-sheet__description::after {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  right: 16px;
  bottom: 0;
  left: 16px;
  border-bottom: 1px solid #ebedf0;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}

.van-action-sheet__loading-icon .van-loading__spinner {
  width: 22px;
  height: 22px;
}

.van-action-sheet__close {
  position: absolute;
  top: 0;
  right: 0;
  z-index: 1;
  padding: 0 16px;
  color: #c8c9cc;
  font-size: 22px;
  line-height: inherit;
}

.van-action-sheet__close:active {
  color: #969799;
}

.van-goods-action {
  position: fixed;
  right: 0;
  bottom: 0;
  left: 0;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  box-sizing: content-box;
  height: 50px;
  padding-bottom: constant(safe-area-inset-bottom);
  padding-bottom: env(safe-area-inset-bottom);
  background-color: #fff;
}

.van-goods-action--unfit {
  padding-bottom: 0;
}

.van-dialog {
  position: fixed;
  top: 45%;
  left: 50%;
  width: 320px;
  overflow: hidden;
  font-size: 16px;
  background-color: #fff;
  border-radius: 16px;
  -webkit-transform: translate3d(-50%, -50%, 0);
  transform: translate3d(-50%, -50%, 0);
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transition: 0.3s;
  transition: 0.3s;
  -webkit-transition-property: opacity, -webkit-transform;
  transition-property: opacity, -webkit-transform;
  transition-property: transform, opacity;
  transition-property: transform, opacity, -webkit-transform;
}

@media (max-width: 321px) {
  .van-dialog {
    width: 90%;
  }
}

.van-dialog__header {
  padding-top: 26px;
  font-weight: 500;
  line-height: 24px;
  text-align: center;
}

.van-dialog__header--isolated {
  padding: 24px 0;
}

.van-dialog__content--isolated {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  min-height: 104px;
}

.van-dialog__message {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  max-height: 60vh;
  padding: 26px 24px;
  overflow-y: auto;
  font-size: 14px;
  line-height: 20px;
  white-space: pre-wrap;
  text-align: center;
  word-wrap: break-word;
  -webkit-overflow-scrolling: touch;
}

.van-dialog__message--has-title {
  padding-top: 8px;
  color: #646566;
}

.van-dialog__message--left {
  text-align: left;
}

.van-dialog__message--right {
  text-align: right;
}

.van-dialog__footer {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  overflow: hidden;
  -webkit-user-select: none;
  user-select: none;
}

.van-dialog__confirm,
.van-dialog__cancel {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  height: 48px;
  margin: 0;
  border: 0;
}

.van-dialog__confirm,
.van-dialog__confirm:active {
  color: #ee0a24;
}

.van-dialog--round-button .van-dialog__footer {
  position: relative;
  height: auto;
  padding: 8px 24px 16px;
}

.van-dialog--round-button .van-dialog__message {
  padding-bottom: 16px;
  color: #323233;
}

.van-dialog--round-button .van-dialog__confirm,
.van-dialog--round-button .van-dialog__cancel {
  height: 36px;
}

.van-dialog--round-button .van-dialog__confirm {
  color: #fff;
}

.van-dialog-bounce-enter {
  -webkit-transform: translate3d(-50%, -50%, 0) scale(0.7);
  transform: translate3d(-50%, -50%, 0) scale(0.7);
  opacity: 0;
}

.van-dialog-bounce-leave-active {
  -webkit-transform: translate3d(-50%, -50%, 0) scale(0.9);
  transform: translate3d(-50%, -50%, 0) scale(0.9);
  opacity: 0;
}

.van-contact-edit {
  padding: 16px;
}

.van-contact-edit__fields {
  overflow: hidden;
  border-radius: 4px;
}

.van-contact-edit__fields .van-field__label {
  width: 4.1em;
}

.van-contact-edit__switch-cell {
  margin-top: 10px;
  padding-top: 9px;
  padding-bottom: 9px;
  border-radius: 4px;
}

.van-contact-edit__buttons {
  padding: 32px 0;
}

.van-contact-edit .van-button {
  margin-bottom: 12px;
  font-size: 16px;
}

.van-address-edit {
  padding: 12px;
}

.van-address-edit__fields {
  overflow: hidden;
  border-radius: 8px;
}

.van-address-edit__fields .van-field__label {
  width: 4.1em;
}

.van-address-edit__default {
  margin-top: 12px;
  overflow: hidden;
  border-radius: 8px;
}

.van-address-edit__buttons {
  padding: 32px 4px;
}

.van-address-edit__buttons .van-button {
  margin-bottom: 12px;
}

.van-address-edit-detail {
  padding: 0;
}

.van-address-edit-detail__search-item {
  background-color: #f2f3f5;
}

.van-address-edit-detail__keyword {
  color: #ee0a24;
}

.van-address-edit-detail__finish {
  color: #1989fa;
  font-size: 12px;
}

.van-radio-group--horizontal {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
}

.van-contact-list {
  box-sizing: border-box;
  height: 100%;
  padding-bottom: 80px;
}

.van-contact-list__item {
  padding: 16px;
}

.van-contact-list__item-value {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  padding-right: 32px;
  padding-left: 8px;
}

.van-contact-list__item-tag {
  -webkit-box-flex: 0;
  -webkit-flex: none;
  flex: none;
  margin-left: 8px;
  padding-top: 0;
  padding-bottom: 0;
  line-height: 1.4em;
}

.van-contact-list__group {
  box-sizing: border-box;
  height: 100%;
  overflow-y: scroll;
  -webkit-overflow-scrolling: touch;
}

.van-contact-list__edit {
  font-size: 16px;
}

.van-contact-list__bottom {
  position: fixed;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 999;
  padding: 0 16px;
  padding-bottom: constant(safe-area-inset-bottom);
  padding-bottom: env(safe-area-inset-bottom);
  background-color: #fff;
}

.van-contact-list__add {
  height: 40px;
  margin: 5px 0;
}

.van-address-list {
  box-sizing: border-box;
  height: 100%;
  padding: 12px 12px 80px;
}

.van-address-list__bottom {
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 999;
  box-sizing: border-box;
  width: 100%;
  padding: 0 16px;
  padding-bottom: constant(safe-area-inset-bottom);
  padding-bottom: env(safe-area-inset-bottom);
  background-color: #fff;
}

.van-address-list__add {
  height: 40px;
  margin: 5px 0;
}

.van-address-list__disabled-text {
  padding: 20px 0 16px;
  color: #969799;
  font-size: 14px;
  line-height: 20px;
}

.van-address-item {
  padding: 12px;
  background-color: #fff;
  border-radius: 8px;
}

.van-address-item:not(:last-child) {
  margin-bottom: 12px;
}

.van-address-item__value {
  padding-right: 44px;
}

.van-address-item__name {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  margin-bottom: 8px;
  font-size: 16px;
  line-height: 22px;
}

.van-address-item__tag {
  -webkit-box-flex: 0;
  -webkit-flex: none;
  flex: none;
  margin-left: 8px;
  padding-top: 0;
  padding-bottom: 0;
  line-height: 1.4em;
}

.van-address-item__address {
  color: #323233;
  font-size: 13px;
  line-height: 18px;
}

.van-address-item--disabled .van-address-item__name,
.van-address-item--disabled .van-address-item__address {
  color: #c8c9cc;
}

.van-address-item__edit {
  position: absolute;
  top: 50%;
  right: 16px;
  color: #969799;
  font-size: 20px;
  -webkit-transform: translate(0, -50%);
  transform: translate(0, -50%);
}

.van-address-item .van-cell {
  padding: 0;
}

.van-address-item .van-radio__label {
  margin-left: 12px;
}

.van-address-item .van-radio__icon--checked .van-icon {
  background-color: #ee0a24;
  border-color: #ee0a24;
}

.van-badge {
  display: inline-block;
  box-sizing: border-box;
  min-width: 16px;
  padding: 0 3px;
  color: #fff;
  font-weight: 500;
  font-size: 12px;
  font-family: -apple-system-font, Helvetica Neue, Arial, sans-serif;
  line-height: 1.2;
  text-align: center;
  background-color: #ee0a24;
  border: 1px solid #fff;
  border-radius: 999px;
}

.van-badge--fixed {
  position: absolute;
  top: 0;
  right: 0;
  -webkit-transform: translate(50%, -50%);
  transform: translate(50%, -50%);
  -webkit-transform-origin: 100%;
  transform-origin: 100%;
}

.van-badge--dot {
  width: 8px;
  min-width: 0;
  height: 8px;
  background-color: #ee0a24;
  border-radius: 100%;
}

.van-badge__wrapper {
  position: relative;
  display: inline-block;
}

.van-tab__pane,
.van-tab__pane-wrapper {
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  box-sizing: border-box;
  width: 100%;
}

.van-tab__pane-wrapper--inactive {
  height: 0;
  overflow: visible;
}

.van-sticky--fixed {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  z-index: 99;
}

.van-tab {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  box-sizing: border-box;
  padding: 0 4px;
  color: #646566;
  font-size: 14px;
  line-height: 20px;
  cursor: pointer;
}

.van-tab--active {
  color: #323233;
  font-weight: 500;
}

.van-tab--disabled {
  color: #c8c9cc;
  cursor: not-allowed;
}

.van-tab__text--ellipsis {
  display: -webkit-box;
  overflow: hidden;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
}

.van-tab__text-wrapper {
  position: relative;
}

.van-tabs {
  position: relative;
}

.van-tabs__wrap {
  overflow: hidden;
}

.van-tabs__wrap--page-top {
  position: fixed;
}

.van-tabs__wrap--content-bottom {
  top: auto;
  bottom: 0;
}

.van-tabs__wrap--scrollable .van-tab {
  -webkit-box-flex: 1;
  -webkit-flex: 1 0 auto;
  flex: 1 0 auto;
  padding: 0 12px;
}

.van-tabs__wrap--scrollable .van-tabs__nav {
  overflow-x: auto;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
}

.van-tabs__wrap--scrollable .van-tabs__nav::-webkit-scrollbar {
  display: none;
}

.van-tabs__nav {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  background-color: #fff;
  -webkit-user-select: none;
  user-select: none;
}

.van-tabs__nav--line {
  box-sizing: content-box;
  height: 100%;
  padding-bottom: 15px;
  /* 15px padding to hide scrollbar in mobile safari */
}

.van-tabs__nav--line.van-tabs__nav--complete {
  padding-right: 8px;
  padding-left: 8px;
}

.van-tabs__nav--card {
  box-sizing: border-box;
  height: 30px;
  margin: 0 16px;
  border: 1px solid #ee0a24;
  border-radius: 2px;
}

.van-tabs__nav--card .van-tab {
  color: #ee0a24;
  border-right: 1px solid #ee0a24;
}

.van-tabs__nav--card .van-tab:last-child {
  border-right: none;
}

.van-tabs__nav--card .van-tab.van-tab--active {
  color: #fff;
  background-color: #ee0a24;
}

.van-tabs__nav--card .van-tab--disabled {
  color: #c8c9cc;
}

.van-tabs__line {
  position: absolute;
  bottom: 15px;
  left: 0;
  z-index: 1;
  width: 40px;
  height: 3px;
  background-color: #ee0a24;
  border-radius: 3px;
}

.van-tabs__track {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  width: 100%;
  height: 100%;
  will-change: left;
}

.van-tabs__content--animated {
  overflow: hidden;
}

.van-tabs--line .van-tabs__wrap {
  height: 44px;
}

.van-tabs--card > .van-tabs__wrap {
  height: 30px;
}

.van-coupon-list {
  position: relative;
  height: 100%;
  background-color: #f7f8fa;
}

.van-coupon-list__field {
  padding: 5px 0 5px 16px;
}

.van-coupon-list__field .van-field__body {
  height: 34px;
  padding-left: 12px;
  line-height: 34px;
  background: #f7f8fa;
  border-radius: 17px;
}

.van-coupon-list__field .van-field__body::-webkit-input-placeholder {
  color: #c8c9cc;
}

.van-coupon-list__field .van-field__body::placeholder {
  color: #c8c9cc;
}

.van-coupon-list__field .van-field__clear {
  margin-right: 0;
}

.van-coupon-list__exchange-bar {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  background-color: #fff;
}

.van-coupon-list__exchange {
  -webkit-box-flex: 0;
  -webkit-flex: none;
  flex: none;
  height: 32px;
  font-size: 16px;
  line-height: 30px;
  border: 0;
}

.van-coupon-list .van-tabs__wrap {
  box-shadow: 0 6px 12px -12px #969799;
}

.van-coupon-list__list {
  box-sizing: border-box;
  padding: 16px 0 24px;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

.van-coupon-list__list--with-bottom {
  padding-bottom: 66px;
}

.van-coupon-list__bottom {
  position: absolute;
  bottom: 0;
  left: 0;
  z-index: 999;
  box-sizing: border-box;
  width: 100%;
  padding: 5px 16px;
  font-weight: 500;
  background-color: #fff;
}

.van-coupon-list__close {
  height: 40px;
}

.van-coupon-list__empty {
  padding-top: 60px;
  text-align: center;
}

.van-coupon-list__empty p {
  margin: 16px 0;
  color: #969799;
  font-size: 14px;
  line-height: 20px;
}

.van-coupon-list__empty img {
  width: 200px;
  height: 200px;
}

.van-cascader__header {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: justify;
  -webkit-justify-content: space-between;
  justify-content: space-between;
  height: 48px;
  padding: 0 16px;
}

.van-cascader__title {
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
}

.van-cascader__close-icon {
  color: #c8c9cc;
  font-size: 22px;
}

.van-cascader__close-icon:active {
  color: #969799;
}

.van-cascader__tabs .van-tab {
  -webkit-box-flex: 0;
  -webkit-flex: none;
  flex: none;
  padding: 0 10px;
}

.van-cascader__tabs.van-tabs--line .van-tabs__wrap {
  height: 48px;
}

.van-cascader__tabs .van-tabs__nav--complete {
  padding-right: 6px;
  padding-left: 6px;
}

.van-cascader__tab {
  color: #323233;
  font-weight: 500;
}

.van-cascader__tab--unselected {
  color: #969799;
  font-weight: normal;
}

.van-cascader__option {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: justify;
  -webkit-justify-content: space-between;
  justify-content: space-between;
  padding: 10px 16px;
  font-size: 14px;
  line-height: 20px;
}

.van-cascader__option:active {
  background-color: #f2f3f5;
}

.van-cascader__option--selected {
  color: #ee0a24;
  font-weight: 500;
}

.van-cascader__selected-icon {
  font-size: 18px;
}

.van-cascader__options {
  box-sizing: border-box;
  height: 384px;
  padding-top: 6px;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

.van-cell-group {
  background-color: #fff;
}

.van-cell-group--inset {
  margin: 0 16px;
  overflow: hidden;
  border-radius: 8px;
}

.van-cell-group__title {
  padding: 16px 16px 8px;
  color: #969799;
  font-size: 14px;
  line-height: 16px;
}

.van-cell-group__title--inset {
  padding: 16px 16px 8px 32px;
}

.van-panel {
  background: #fff;
}

.van-panel__header-value {
  color: #ee0a24;
}

.van-panel__footer {
  padding: 8px 16px;
}

.van-checkbox-group--horizontal {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
}

.van-circle {
  position: relative;
  display: inline-block;
  width: 100px;
  height: 100px;
  text-align: center;
}

.van-circle svg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.van-circle__layer {
  stroke: #fff;
}

.van-circle__hover {
  fill: none;
  stroke: #1989fa;
  stroke-linecap: round;
}

.van-circle__text {
  position: absolute;
  top: 50%;
  left: 0;
  box-sizing: border-box;
  width: 100%;
  padding: 0 4px;
  color: #323233;
  font-weight: 500;
  font-size: 14px;
  line-height: 20px;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
}

.van-col {
  float: left;
  box-sizing: border-box;
  min-height: 1px;
}

.van-col--1 {
  width: 4.16666667%;
}

.van-col--offset-1 {
  margin-left: 4.16666667%;
}

.van-col--2 {
  width: 8.33333333%;
}

.van-col--offset-2 {
  margin-left: 8.33333333%;
}

.van-col--3 {
  width: 12.5%;
}

.van-col--offset-3 {
  margin-left: 12.5%;
}

.van-col--4 {
  width: 16.66666667%;
}

.van-col--offset-4 {
  margin-left: 16.66666667%;
}

.van-col--5 {
  width: 20.83333333%;
}

.van-col--offset-5 {
  margin-left: 20.83333333%;
}

.van-col--6 {
  width: 25%;
}

.van-col--offset-6 {
  margin-left: 25%;
}

.van-col--7 {
  width: 29.16666667%;
}

.van-col--offset-7 {
  margin-left: 29.16666667%;
}

.van-col--8 {
  width: 33.33333333%;
}

.van-col--offset-8 {
  margin-left: 33.33333333%;
}

.van-col--9 {
  width: 37.5%;
}

.van-col--offset-9 {
  margin-left: 37.5%;
}

.van-col--10 {
  width: 41.66666667%;
}

.van-col--offset-10 {
  margin-left: 41.66666667%;
}

.van-col--11 {
  width: 45.83333333%;
}

.van-col--offset-11 {
  margin-left: 45.83333333%;
}

.van-col--12 {
  width: 50%;
}

.van-col--offset-12 {
  margin-left: 50%;
}

.van-col--13 {
  width: 54.16666667%;
}

.van-col--offset-13 {
  margin-left: 54.16666667%;
}

.van-col--14 {
  width: 58.33333333%;
}

.van-col--offset-14 {
  margin-left: 58.33333333%;
}

.van-col--15 {
  width: 62.5%;
}

.van-col--offset-15 {
  margin-left: 62.5%;
}

.van-col--16 {
  width: 66.66666667%;
}

.van-col--offset-16 {
  margin-left: 66.66666667%;
}

.van-col--17 {
  width: 70.83333333%;
}

.van-col--offset-17 {
  margin-left: 70.83333333%;
}

.van-col--18 {
  width: 75%;
}

.van-col--offset-18 {
  margin-left: 75%;
}

.van-col--19 {
  width: 79.16666667%;
}

.van-col--offset-19 {
  margin-left: 79.16666667%;
}

.van-col--20 {
  width: 83.33333333%;
}

.van-col--offset-20 {
  margin-left: 83.33333333%;
}

.van-col--21 {
  width: 87.5%;
}

.van-col--offset-21 {
  margin-left: 87.5%;
}

.van-col--22 {
  width: 91.66666667%;
}

.van-col--offset-22 {
  margin-left: 91.66666667%;
}

.van-col--23 {
  width: 95.83333333%;
}

.van-col--offset-23 {
  margin-left: 95.83333333%;
}

.van-col--24 {
  width: 100%;
}

.van-col--offset-24 {
  margin-left: 100%;
}

.van-count-down {
  color: #323233;
  font-size: 14px;
  line-height: 20px;
}

.van-divider {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  margin: 16px 0;
  color: #969799;
  font-size: 14px;
  line-height: 24px;
  border-color: #ebedf0;
  border-style: solid;
  border-width: 0;
}

.van-divider::before,
.van-divider::after {
  display: block;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  box-sizing: border-box;
  height: 1px;
  border-color: inherit;
  border-style: inherit;
  border-width: 1px 0 0;
}

.van-divider::before {
  content: "";
}

.van-divider--hairline::before,
.van-divider--hairline::after {
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}

.van-divider--dashed {
  border-style: dashed;
}

.van-divider--content-center::before,
.van-divider--content-left::before,
.van-divider--content-right::before {
  margin-right: 16px;
}

.van-divider--content-center::after,
.van-divider--content-left::after,
.van-divider--content-right::after {
  margin-left: 16px;
  content: "";
}

.van-divider--content-left::before {
  max-width: 10%;
}

.van-divider--content-right::after {
  max-width: 10%;
}

.van-dropdown-menu {
  -webkit-user-select: none;
  user-select: none;
}

.van-dropdown-menu__bar {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  height: 48px;
  background-color: #fff;
  box-shadow: 0 2px 12px rgba(100, 101, 102, 0.12);
}

.van-dropdown-menu__bar--opened {
  z-index: 11;
}

.van-dropdown-menu__item {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  min-width: 0;
  cursor: pointer;
}

.van-dropdown-menu__item:active {
  opacity: 0.7;
}

.van-dropdown-menu__item--disabled:active {
  opacity: 1;
}

.van-dropdown-menu__item--disabled .van-dropdown-menu__title {
  color: #969799;
}

.van-dropdown-menu__title {
  position: relative;
  box-sizing: border-box;
  max-width: 100%;
  padding: 0 8px;
  color: #323233;
  font-size: 15px;
  line-height: 22px;
}

.van-dropdown-menu__title::after {
  position: absolute;
  top: 50%;
  right: -4px;
  margin-top: -5px;
  border: 3px solid;
  border-color: transparent transparent #dcdee0 #dcdee0;
  -webkit-transform: rotate(-45deg);
  transform: rotate(-45deg);
  opacity: 0.8;
  content: "";
}

.van-dropdown-menu__title--active {
  color: #ee0a24;
}

.van-dropdown-menu__title--active::after {
  border-color: transparent transparent currentColor currentColor;
}

.van-dropdown-menu__title--down::after {
  margin-top: -1px;
  -webkit-transform: rotate(135deg);
  transform: rotate(135deg);
}

.van-empty {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  box-sizing: border-box;
  padding: 32px 0;
}

.van-empty__image {
  width: 160px;
  height: 160px;
}

.van-empty__image img {
  width: 100%;
  height: 100%;
}

.van-empty__description {
  margin-top: 16px;
  padding: 0 60px;
  color: #969799;
  font-size: 14px;
  line-height: 20px;
}

.van-empty__bottom {
  margin-top: 24px;
}

.van-grid {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
}

.van-swipe {
  position: relative;
  overflow: hidden;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  cursor: grab;
  -webkit-user-select: none;
  user-select: none;
}

.van-swipe__track {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  height: 100%;
}

.van-swipe__track--vertical {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
}

.van-swipe__indicators {
  position: absolute;
  bottom: 12px;
  left: 50%;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-transform: translateX(-50%);
  transform: translateX(-50%);
}

.van-swipe__indicators--vertical {
  top: 50%;
  bottom: auto;
  left: 12px;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
}

.van-swipe__indicators--vertical .van-swipe__indicator:not(:last-child) {
  margin-bottom: 6px;
}

.van-swipe__indicator {
  width: 6px;
  height: 6px;
  background-color: #ebedf0;
  border-radius: 100%;
  opacity: 0.3;
  -webkit-transition: opacity 0.2s, background-color 0.2s;
  transition: opacity 0.2s, background-color 0.2s;
}

.van-swipe__indicator:not(:last-child) {
  margin-right: 6px;
}

.van-swipe__indicator--active {
  background-color: #1989fa;
  opacity: 1;
}

.van-swipe-item {
  position: relative;
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  width: 100%;
  height: 100%;
}

.van-image-preview {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.van-image-preview__swipe {
  height: 100%;
}

.van-image-preview__swipe-item {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  overflow: hidden;
}

.van-image-preview__cover {
  position: absolute;
  top: 0;
  left: 0;
}

.van-image-preview__image {
  width: 100%;
  -webkit-transition-property: -webkit-transform;
  transition-property: -webkit-transform;
  transition-property: transform;
  transition-property: transform, -webkit-transform;
}

.van-image-preview__image--vertical {
  width: auto;
  height: 100%;
}

.van-image-preview__image img {
  -webkit-user-drag: none;
}

.van-image-preview__image .van-image__error {
  top: 30%;
  height: 40%;
}

.van-image-preview__image .van-image__error-icon {
  font-size: 36px;
}

.van-image-preview__image .van-image__loading {
  background-color: transparent;
}

.van-image-preview__index {
  position: absolute;
  top: 16px;
  left: 50%;
  color: #fff;
  font-size: 14px;
  line-height: 20px;
  text-shadow: 0 1px 1px #323233;
  -webkit-transform: translate(-50%, 0);
  transform: translate(-50%, 0);
}

.van-image-preview__overlay {
  background-color: rgba(0, 0, 0, 0.9);
}

.van-image-preview__close-icon {
  position: absolute;
  z-index: 1;
  color: #c8c9cc;
  font-size: 22px;
  cursor: pointer;
}

.van-image-preview__close-icon:active {
  color: #969799;
}

.van-image-preview__close-icon--top-left {
  top: 16px;
  left: 16px;
}

.van-image-preview__close-icon--top-right {
  top: 16px;
  right: 16px;
}

.van-image-preview__close-icon--bottom-left {
  bottom: 16px;
  left: 16px;
}

.van-image-preview__close-icon--bottom-right {
  right: 16px;
  bottom: 16px;
}

.van-uploader {
  position: relative;
  display: inline-block;
}

.van-uploader__wrapper {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
}

.van-uploader__wrapper--disabled {
  opacity: 0.5;
}

.van-uploader__input {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  cursor: pointer;
  opacity: 0;
}

.van-uploader__input-wrapper {
  position: relative;
}

.van-uploader__input:disabled {
  cursor: not-allowed;
}

.van-uploader__upload {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  box-sizing: border-box;
  width: 80px;
  height: 80px;
  margin: 0 8px 8px 0;
  background-color: #f7f8fa;
}

.van-uploader__upload:active {
  background-color: #f2f3f5;
}

.van-uploader__upload--readonly:active {
  background-color: #f7f8fa;
}

.van-uploader__upload-icon {
  color: #dcdee0;
  font-size: 24px;
}

.van-uploader__upload-text {
  margin-top: 8px;
  color: #969799;
  font-size: 12px;
}

.van-uploader__preview {
  position: relative;
  margin: 0 8px 8px 0;
  cursor: pointer;
}

.van-uploader__preview-image {
  display: block;
  width: 80px;
  height: 80px;
  overflow: hidden;
}

.van-uploader__preview-delete {
  position: absolute;
  top: 0;
  right: 0;
  width: 14px;
  height: 14px;
  background-color: rgba(0, 0, 0, 0.7);
  border-radius: 0 0 0 12px;
}

.van-uploader__preview-delete-icon {
  position: absolute;
  top: -2px;
  right: -2px;
  color: #fff;
  font-size: 16px;
  -webkit-transform: scale(0.5);
  transform: scale(0.5);
}

.van-uploader__preview-cover {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

.van-uploader__mask {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  color: #fff;
  background-color: rgba(50, 50, 51, 0.88);
}

.van-uploader__mask-icon {
  font-size: 22px;
}

.van-uploader__mask-message {
  margin-top: 6px;
  padding: 0 4px;
  font-size: 12px;
  line-height: 14px;
}

.van-uploader__loading {
  width: 22px;
  height: 22px;
  color: #fff;
}

.van-uploader__file {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  width: 80px;
  height: 80px;
  background-color: #f7f8fa;
}

.van-uploader__file-icon {
  color: #646566;
  font-size: 20px;
}

.van-uploader__file-name {
  box-sizing: border-box;
  width: 100%;
  margin-top: 8px;
  padding: 0 4px;
  color: #646566;
  font-size: 12px;
  text-align: center;
}

.van-index-anchor {
  z-index: 1;
  box-sizing: border-box;
  padding: 0 16px;
  color: #323233;
  font-weight: 500;
  font-size: 14px;
  line-height: 32px;
  background-color: transparent;
}

.van-index-anchor--sticky {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  color: #ee0a24;
  background-color: #fff;
}

.van-index-bar__sidebar {
  position: fixed;
  top: 50%;
  right: 0;
  z-index: 2;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  text-align: center;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}

.van-index-bar__index {
  padding: 0 8px 0 16px;
  font-weight: 500;
  font-size: 10px;
  line-height: 14px;
}

.van-index-bar__index--active {
  color: #ee0a24;
}

.van-pagination {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  font-size: 14px;
}

.van-pagination__item,
.van-pagination__page-desc {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
}

.van-pagination__item {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  box-sizing: border-box;
  min-width: 36px;
  height: 40px;
  color: #1989fa;
  background-color: #fff;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}

.van-pagination__item:active {
  color: #fff;
  background-color: #1989fa;
}

.van-pagination__item::after {
  border-width: 1px 0 1px 1px;
}

.van-pagination__item:last-child::after {
  border-right-width: 1px;
}

.van-pagination__item--active {
  color: #fff;
  background-color: #1989fa;
}

.van-pagination__prev,
.van-pagination__next {
  padding: 0 4px;
  cursor: pointer;
}

.van-pagination__item--disabled,
.van-pagination__item--disabled:active {
  color: #646566;
  background-color: #f7f8fa;
  cursor: not-allowed;
  opacity: 0.5;
}

.van-pagination__page {
  -webkit-box-flex: 0;
  -webkit-flex-grow: 0;
  flex-grow: 0;
}

.van-pagination__page-desc {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  height: 40px;
  color: #646566;
}

.van-pagination--simple .van-pagination__prev::after,
.van-pagination--simple .van-pagination__next::after {
  border-width: 1px;
}

.van-password-input {
  position: relative;
  margin: 0 16px;
  -webkit-user-select: none;
  user-select: none;
}

.van-password-input__info,
.van-password-input__error-info {
  margin-top: 16px;
  font-size: 14px;
  text-align: center;
}

.van-password-input__info {
  color: #969799;
}

.van-password-input__error-info {
  color: #ee0a24;
}

.van-password-input__security {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  width: 100%;
  height: 50px;
  cursor: pointer;
}

.van-password-input__security::after {
  border-radius: 6px;
}

.van-password-input__security li {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  height: 100%;
  font-size: 20px;
  line-height: 1.2;
  background-color: #fff;
}

.van-password-input__security i {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 10px;
  height: 10px;
  background-color: #000;
  border-radius: 100%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  visibility: hidden;
}

.van-password-input__cursor {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 1px;
  height: 40%;
  background-color: #323233;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  -webkit-animation: 1s van-cursor-flicker infinite;
  animation: 1s van-cursor-flicker infinite;
}

@-webkit-keyframes van-cursor-flicker {
  from {
    opacity: 0;
  }

  50% {
    opacity: 1;
  }

  100% {
    opacity: 0;
  }
}

@keyframes van-cursor-flicker {
  from {
    opacity: 0;
  }

  50% {
    opacity: 1;
  }

  100% {
    opacity: 0;
  }
}

.van-progress {
  position: relative;
  height: 4px;
  background: #ebedf0;
  border-radius: 4px;
}

.van-progress__portion {
  position: absolute;
  left: 0;
  height: 100%;
  background: #1989fa;
  border-radius: inherit;
}

.van-progress__pivot {
  position: absolute;
  top: 50%;
  box-sizing: border-box;
  min-width: 3.6em;
  padding: 0 5px;
  color: #fff;
  font-size: 10px;
  line-height: 1.6;
  text-align: center;
  word-break: keep-all;
  background-color: #1989fa;
  border-radius: 1em;
  -webkit-transform: translate(0, -50%);
  transform: translate(0, -50%);
}

.van-row::after {
  display: table;
  clear: both;
  content: "";
}

.van-row--flex {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
}

.van-row--flex::after {
  display: none;
}

.van-row--justify-center {
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
}

.van-row--justify-end {
  -webkit-box-pack: end;
  -webkit-justify-content: flex-end;
  justify-content: flex-end;
}

.van-row--justify-space-between {
  -webkit-box-pack: justify;
  -webkit-justify-content: space-between;
  justify-content: space-between;
}

.van-row--justify-space-around {
  -webkit-justify-content: space-around;
  justify-content: space-around;
}

.van-row--align-center {
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
}

.van-row--align-bottom {
  -webkit-box-align: end;
  -webkit-align-items: flex-end;
  align-items: flex-end;
}

.van-sidebar {
  width: 80px;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

.van-tree-select {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  font-size: 14px;
  -webkit-user-select: none;
  user-select: none;
}

.van-tree-select__nav {
  -webkit-box-flex: 1;
  -webkit-flex: 1;
  flex: 1;
  overflow-y: auto;
  background-color: #f7f8fa;
  -webkit-overflow-scrolling: touch;
}

.van-tree-select__nav-item {
  padding: 14px 12px;
}

.van-tree-select__content {
  -webkit-box-flex: 2;
  -webkit-flex: 2;
  flex: 2;
  overflow-y: auto;
  background-color: #fff;
  -webkit-overflow-scrolling: touch;
}

.van-tree-select__item {
  position: relative;
  padding: 0 32px 0 16px;
  font-weight: 500;
  line-height: 48px;
  cursor: pointer;
}

.van-tree-select__item--active {
  color: #ee0a24;
}

.van-tree-select__item--disabled {
  color: #c8c9cc;
  cursor: not-allowed;
}

.van-tree-select__selected {
  position: absolute;
  top: 50%;
  right: 16px;
  margin-top: -8px;
  font-size: 16px;
}

.van-skeleton {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  padding: 0 16px;
}

.van-skeleton__avatar {
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  width: 32px;
  height: 32px;
  margin-right: 16px;
  background-color: #f2f3f5;
}

.van-skeleton__avatar--round {
  border-radius: 999px;
}

.van-skeleton__content {
  width: 100%;
}

.van-skeleton__avatar + .van-skeleton__content {
  padding-top: 8px;
}

.van-skeleton__row,
.van-skeleton__title {
  height: 16px;
  background-color: #f2f3f5;
}

.van-skeleton__title {
  width: 40%;
  margin: 0;
}

.van-skeleton__row:not(:first-child) {
  margin-top: 12px;
}

.van-skeleton__title + .van-skeleton__row {
  margin-top: 20px;
}

.van-skeleton--animate {
  -webkit-animation: van-skeleton-blink 1.2s ease-in-out infinite;
  animation: van-skeleton-blink 1.2s ease-in-out infinite;
}

.van-skeleton--round .van-skeleton__row,
.van-skeleton--round .van-skeleton__title {
  border-radius: 999px;
}

@-webkit-keyframes van-skeleton-blink {
  50% {
    opacity: 0.6;
  }
}

@keyframes van-skeleton-blink {
  50% {
    opacity: 0.6;
  }
}

.van-stepper {
  font-size: 0;
  -webkit-user-select: none;
  user-select: none;
}

.van-stepper__minus,
.van-stepper__plus {
  position: relative;
  box-sizing: border-box;
  width: 28px;
  height: 28px;
  margin: 0;
  padding: 0;
  color: #323233;
  vertical-align: middle;
  background-color: #f2f3f5;
  border: 0;
  cursor: pointer;
}

.van-stepper__minus::before,
.van-stepper__plus::before {
  width: 50%;
  height: 1px;
}

.van-stepper__minus::after,
.van-stepper__plus::after {
  width: 1px;
  height: 50%;
}

.van-stepper__minus::before,
.van-stepper__plus::before,
.van-stepper__minus::after,
.van-stepper__plus::after {
  position: absolute;
  top: 50%;
  left: 50%;
  background-color: currentColor;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  content: "";
}

.van-stepper__minus:active,
.van-stepper__plus:active {
  background-color: #e8e8e8;
}

.van-stepper__minus--disabled,
.van-stepper__plus--disabled {
  color: #c8c9cc;
  background-color: #f7f8fa;
  cursor: not-allowed;
}

.van-stepper__minus--disabled:active,
.van-stepper__plus--disabled:active {
  background-color: #f7f8fa;
}

.van-stepper__minus {
  border-radius: 4px 0 0 4px;
}

.van-stepper__minus::after {
  display: none;
}

.van-stepper__plus {
  border-radius: 0 4px 4px 0;
}

.van-stepper__input {
  box-sizing: border-box;
  width: 32px;
  height: 28px;
  margin: 0 2px;
  padding: 0;
  color: #323233;
  font-size: 14px;
  line-height: normal;
  text-align: center;
  vertical-align: middle;
  background-color: #f2f3f5;
  border: 0;
  border-width: 1px 0;
  border-radius: 0;
  -webkit-appearance: none;
}

.van-stepper__input:disabled {
  color: #c8c9cc;
  background-color: #f2f3f5;
  -webkit-text-fill-color: #c8c9cc;
  opacity: 1;
}

.van-stepper__input:read-only {
  cursor: default;
}

.van-stepper--round .van-stepper__input {
  background-color: transparent;
}

.van-stepper--round .van-stepper__plus,
.van-stepper--round .van-stepper__minus {
  border-radius: 100%;
}

.van-stepper--round .van-stepper__plus:active,
.van-stepper--round .van-stepper__minus:active {
  opacity: 0.7;
}

.van-stepper--round .van-stepper__plus--disabled,
.van-stepper--round .van-stepper__minus--disabled,
.van-stepper--round .van-stepper__plus--disabled:active,
.van-stepper--round .van-stepper__minus--disabled:active {
  opacity: 0.3;
}

.van-stepper--round .van-stepper__plus {
  color: #fff;
  background-color: #ee0a24;
}

.van-stepper--round .van-stepper__minus {
  color: #ee0a24;
  background-color: #fff;
  border: 1px solid #ee0a24;
}

.van-sku {
  /* sku row */
}

.van-sku-container {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: stretch;
  -webkit-align-items: stretch;
  align-items: stretch;
  min-height: 50%;
  max-height: 80%;
  overflow-y: visible;
  font-size: 14px;
  background: #fff;
}

.van-sku-body {
  -webkit-box-flex: 1;
  -webkit-flex: 1 1 auto;
  flex: 1 1 auto;
  min-height: 44px;
  overflow-y: scroll;
  -webkit-overflow-scrolling: touch;
}

.van-sku-body::-webkit-scrollbar {
  display: none;
}

.van-sku-header {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  margin: 0 16px;
}

.van-sku-header__img-wrap {
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  width: 96px;
  height: 96px;
  margin: 12px 12px 12px 0;
  overflow: hidden;
  border-radius: 4px;
}

.van-sku-header__goods-info {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: end;
  -webkit-justify-content: flex-end;
  justify-content: flex-end;
  padding: 12px 20px 12px 0;
}

.van-sku-header-item {
  margin-top: 8px;
  color: #969799;
  font-size: 12px;
  line-height: 16px;
}

.van-sku__price-symbol {
  font-size: 16px;
  vertical-align: bottom;
}

.van-sku__price-num {
  font-weight: 500;
  font-size: 22px;
  vertical-align: bottom;
  word-wrap: break-word;
}

.van-sku__goods-price {
  margin-left: -2px;
  color: #ee0a24;
}

.van-sku__price-tag {
  position: relative;
  display: inline-block;
  margin-left: 8px;
  padding: 0 5px;
  overflow: hidden;
  color: #ee0a24;
  font-size: 12px;
  line-height: 16px;
  border-radius: 8px;
}

.van-sku__price-tag::before {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: currentColor;
  opacity: 0.1;
  content: "";
}

.van-sku-group-container {
  padding-top: 12px;
}

.van-sku-group-container--hide-soldout .van-sku-row__item--disabled {
  display: none;
}

.van-sku-row {
  margin: 0 16px 12px;
}

.van-sku-row:last-child {
  margin-bottom: 0;
}

.van-sku-row__item,
.van-sku-row__image-item {
  position: relative;
  overflow: hidden;
  color: #323233;
  border-radius: 4px;
  cursor: pointer;
}

.van-sku-row__item::before,
.van-sku-row__image-item::before {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #f7f8fa;
  content: "";
}

.van-sku-row__item--active,
.van-sku-row__image-item--active {
  color: #ee0a24;
}

.van-sku-row__item--active::before,
.van-sku-row__image-item--active::before {
  background: currentColor;
  opacity: 0.1;
}

.van-sku-row__item {
  display: -webkit-inline-box;
  display: -webkit-inline-flex;
  display: inline-flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  min-width: 40px;
  margin: 0 12px 12px 0;
  font-size: 13px;
  line-height: 16px;
  vertical-align: middle;
}

.van-sku-row__item-img {
  z-index: 1;
  width: 24px;
  height: 24px;
  margin: 4px 0 4px 4px;
  object-fit: cover;
  border-radius: 2px;
}

.van-sku-row__item-name {
  z-index: 1;
  padding: 8px;
}

.van-sku-row__item--disabled {
  color: #c8c9cc;
  background: #f2f3f5;
  cursor: not-allowed;
}

.van-sku-row__item--disabled .van-sku-row__item-img {
  opacity: 0.3;
}

.van-sku-row__image {
  margin-right: 0;
}

.van-sku-row__image-item {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  flex-direction: column;
  width: 110px;
  margin: 0 4px 4px 0;
  border: 1px solid transparent;
}

.van-sku-row__image-item:last-child {
  margin-right: 0;
}

.van-sku-row__image-item-img {
  width: 100%;
  height: 110px;
}

.van-sku-row__image-item-img-icon {
  position: absolute;
  top: 0;
  right: 0;
  z-index: 3;
  width: 18px;
  height: 18px;
  color: #fff;
  line-height: 18px;
  text-align: center;
  background-color: rgba(0, 0, 0, 0.4);
  border-bottom-left-radius: 4px;
}

.van-sku-row__image-item-name {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  box-sizing: border-box;
  height: 40px;
  padding: 4px;
  font-size: 12px;
  line-height: 16px;
}

.van-sku-row__image-item-name span {
  word-wrap: break-word;
}

.van-sku-row__image-item--active {
  border-color: currentColor;
}

.van-sku-row__image-item--disabled {
  color: #c8c9cc;
  cursor: not-allowed;
}

.van-sku-row__image-item--disabled::before {
  z-index: 2;
  background: #f2f3f5;
  opacity: 0.4;
}

.van-sku-row__title {
  padding-bottom: 12px;
}

.van-sku-row__title-multiple {
  color: #969799;
}

.van-sku-row__scroller {
  margin: 0 -16px;
  overflow-x: scroll;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
}

.van-sku-row__scroller::-webkit-scrollbar {
  display: none;
}

.van-sku-row__row {
  display: -webkit-inline-box;
  display: -webkit-inline-flex;
  display: inline-flex;
  margin-bottom: 4px;
  padding: 0 16px;
}

.van-sku-row__indicator {
  width: 40px;
  height: 4px;
  background: #ebedf0;
  border-radius: 2px;
}

.van-sku-row__indicator-wrapper {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
  justify-content: center;
  padding-bottom: 16px;
}

.van-sku-row__indicator-slider {
  width: 50%;
  height: 100%;
  background-color: #ee0a24;
  border-radius: 2px;
}

.van-sku-stepper-stock {
  padding: 12px 16px;
  overflow: hidden;
  line-height: 30px;
}

.van-sku__stepper {
  float: right;
  padding-left: 4px;
}

.van-sku__stepper-title {
  float: left;
}

.van-sku__stepper-quota {
  float: right;
  color: #ee0a24;
  font-size: 12px;
}

.van-sku__stock {
  display: inline-block;
  margin-right: 8px;
  color: #969799;
  font-size: 12px;
}

.van-sku__stock-num--highlight {
  color: #ee0a24;
}

.van-sku-messages {
  padding-bottom: 32px;
}

.van-sku-messages__image-cell .van-cell__title {
  max-width: 6.2em;
  margin-right: 12px;
  color: #646566;
  text-align: left;
  word-wrap: break-word;
}

.van-sku-messages__image-cell .van-cell__value {
  overflow: visible;
  text-align: left;
}

.van-sku-messages__image-cell-label {
  color: #969799;
  font-size: 12px;
  line-height: 18px;
}

.van-sku-messages__cell-block {
  position: relative;
}

.van-sku-messages__cell-block::after {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  right: 16px;
  bottom: 0;
  left: 16px;
  border-bottom: 1px solid #ebedf0;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}

.van-sku-messages__cell-block:last-child::after {
  display: none;
}

.van-sku-messages__extra-message {
  margin-top: -2px;
  padding: 0 16px 12px;
  color: #969799;
  font-size: 12px;
  line-height: 18px;
}

.van-sku-actions {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  padding: 8px 16px;
}

.van-sku-actions .van-button {
  height: 40px;
  font-weight: 500;
  font-size: 14px;
  border: none;
  border-radius: 0;
}

.van-sku-actions .van-button:first-of-type {
  border-top-left-radius: 20px;
  border-bottom-left-radius: 20px;
}

.van-sku-actions .van-button:last-of-type {
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
}

.van-sku-actions .van-button--warning {
  background: -webkit-linear-gradient(left, #ffd01e, #ff8917);
  background: linear-gradient(to right, #ffd01e, #ff8917);
}

.van-sku-actions .van-button--danger {
  background: -webkit-linear-gradient(left, #ff6034, #ee0a24);
  background: linear-gradient(to right, #ff6034, #ee0a24);
}

.van-slider {
  position: relative;
  width: 100%;
  height: 2px;
  background-color: #ebedf0;
  border-radius: 999px;
  cursor: pointer;
}

.van-slider::before {
  position: absolute;
  top: -8px;
  right: 0;
  bottom: -8px;
  left: 0;
  content: "";
}

.van-slider__bar {
  position: relative;
  width: 100%;
  height: 100%;
  background-color: #1989fa;
  border-radius: inherit;
  -webkit-transition: all 0.2s;
  transition: all 0.2s;
}

.van-slider__button {
  width: 24px;
  height: 24px;
  background-color: #fff;
  border-radius: 50%;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}

.van-slider__button-wrapper,
.van-slider__button-wrapper-right {
  position: absolute;
  top: 50%;
  right: 0;
  -webkit-transform: translate3d(50%, -50%, 0);
  transform: translate3d(50%, -50%, 0);
  cursor: grab;
}

.van-slider__button-wrapper-left {
  position: absolute;
  top: 50%;
  left: 0;
  -webkit-transform: translate3d(-50%, -50%, 0);
  transform: translate3d(-50%, -50%, 0);
  cursor: grab;
}

.van-slider--disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

.van-slider--disabled .van-slider__button-wrapper,
.van-slider--disabled .van-slider__button-wrapper-left,
.van-slider--disabled .van-slider__button-wrapper-right {
  cursor: not-allowed;
}

.van-slider--vertical {
  display: inline-block;
  width: 2px;
  height: 100%;
}

.van-slider--vertical .van-slider__button-wrapper,
.van-slider--vertical .van-slider__button-wrapper-right {
  top: auto;
  right: 50%;
  bottom: 0;
  -webkit-transform: translate3d(50%, 50%, 0);
  transform: translate3d(50%, 50%, 0);
}

.van-slider--vertical .van-slider__button-wrapper-left {
  top: 0;
  right: 50%;
  left: auto;
  -webkit-transform: translate3d(50%, -50%, 0);
  transform: translate3d(50%, -50%, 0);
}

.van-slider--vertical::before {
  top: 0;
  right: -8px;
  bottom: 0;
  left: -8px;
}

.van-steps {
  overflow: hidden;
  background-color: #fff;
}

.van-steps--horizontal {
  padding: 10px 10px 0;
}

.van-steps--horizontal .van-steps__items {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  margin: 0 0 10px;
  padding-bottom: 22px;
}

.van-steps--vertical {
  padding: 0 0 0 32px;
}

.van-swipe-cell {
  position: relative;
  overflow: hidden;
  cursor: grab;
}

.van-swipe-cell__wrapper {
  -webkit-transition-timing-function: cubic-bezier(0.18, 0.89, 0.32, 1);
  transition-timing-function: cubic-bezier(0.18, 0.89, 0.32, 1);
  -webkit-transition-property: -webkit-transform;
  transition-property: -webkit-transform;
  transition-property: transform;
  transition-property: transform, -webkit-transform;
}

.van-swipe-cell__left,
.van-swipe-cell__right {
  position: absolute;
  top: 0;
  height: 100%;
}

.van-swipe-cell__left {
  left: 0;
  -webkit-transform: translate3d(-100%, 0, 0);
  transform: translate3d(-100%, 0, 0);
}

.van-swipe-cell__right {
  right: 0;
  -webkit-transform: translate3d(100%, 0, 0);
  transform: translate3d(100%, 0, 0);
}

.van-tabbar {
  z-index: 1;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  box-sizing: content-box;
  width: 100%;
  height: 50px;
  padding-bottom: constant(safe-area-inset-bottom);
  padding-bottom: env(safe-area-inset-bottom);
  background-color: #fff;
}

.van-tabbar--fixed {
  position: fixed;
  bottom: 0;
  left: 0;
}

.van-tabbar--unfit {
  padding-bottom: 0;
}
</style>
<style>
@charset "UTF-8";

/* 弹窗样式 */
.popup-box {
  top: 0;
  left: 0;
  position: fixed;
  z-index: 999;
  width: 100%;
  height: 100%;
  background: rgba(56, 54, 54, 0.5490196078);
}

.popup-box .popup-advert {
  width: 40rem;
  position: fixed;
  z-index: 996;
  margin: auto;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  border-radius: 2px;
}

.popup-box .popup-advert .time {
  width: 120px;
  text-align: center;
  position: absolute;
  bottom: 2px;
  background: #fff;
  left: 0;
  right: 0;
  margin: auto;
  z-index: 6;
}

.popup-box .popup-advert .close {
  width: 30px;
  height: 30px;
  position: absolute;
  top: 10rem;
  right: 5rem;
  font-style: normal;
  font-size: 16px;
  text-align: center;
  border-radius: 100%;
  background-color: #efeaea;
  z-index: 9;
  line-height: 27px;
  color: #da0808;
}

.popup-box .popup-advert .advert-content {
  position: relative;
  width: 100%;
  height: 100%;
}

.popup-box .popup-advert .advert-content a {
  display: block;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

.popup-box .popup-advert .advert-content img {
  background-color: transparent;
  text-align: center;
  width: 100%;
  display: block;
}
</style>
<style>
@charset "UTF-8";

/* bonus样式 */
.bonus {
  position: fixed;
  left: 0;
  right: 0;
  top: 187px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}

.bonus .b-content {
  width: 960px;
  background: #ffffff;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  border: 1px solid #065446;
  -webkit-box-shadow: 0 0 12px #065446;
  box-shadow: 0 0 12px #065446;
}

.bonus .b-content .b-title {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  background: #065446;
  height: 36px;
  padding-left: 10px;
  color: #ffffff;
}

.bonus .b-content .b-title span {
  font-size: 14px;
  font-weight: 700;
}

.bonus .b-content .b-title em {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  height: 100%;
  width: 30px;
  cursor: pointer;
  font-size: 16px;
}

.bonus .b-content .b-year {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  height: 22px;
  padding: 15px 0;
}

.bonus .b-content .b-year span {
  margin: 0 3px;
  padding: 0 10px;
  height: 20px;
  line-height: 20px;
  border: 1px solid #a5a5a5;
  background: -webkit-gradient(
    linear,
    left top,
    left bottom,
    from(#fefefe),
    to(#f0f0f0)
  );
  background: linear-gradient(to bottom, #fefefe 0%, #f0f0f0 100%);
  cursor: pointer;
}

.bonus .b-content .b-table {
  padding: 0 10px 10px 10px;
}

.bonus .b-content .b-table table {
  border: 1px solid #00ff00;
}

.bonus .b-content .b-table table tr.tr1 {
  background: #faffe8;
}

.bonus .b-content .b-table table tr.tr2 {
  background: #ccffff;
}

.bonus .b-content .b-table table tr.tr3 {
  background: #ffccff;
}

.bonus .b-content .b-table table tr.tr4 {
  background: #ccccff;
}

.bonus .b-content .b-table table td,
.bonus .b-content .b-table table th {
  padding: 5px 0;
  text-align: center;
  font-size: 14px;
  border-top: 1px solid #00ff00;
  border-right: 1px solid #00ff00;
  color: #000000;
}

/* start 蓝色模板样式 */
.blue .bonus .b-content {
  border: 1px solid #4b58a0;
  -webkit-box-shadow: 0 0 12px #4b58a0;
  box-shadow: 0 0 12px #4b58a0;
}

.blue .bonus .b-content .b-title {
  background: #4b58a0 !important;
}

/* end 蓝色模板样式 */
</style>
<style>
.com-open {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  height: 21px;
  font-size: 13px;
  font-family: Arial;
  line-height: 22px;
  background: #ffffff;
}

.com-open a {
  color: #08f;
  text-decoration: none;
  font-size: 12px;
}

.com-open a:hover {
  color: #d00;
}

.com-open .con {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.com-open .qihao {
  margin-right: 5px;
}

.com-open .sdong {
  margin-left: 5px;
  /*margin-left: 10px;*/
}

.com-open .sdong i {
  padding: 0 2px;
  /*padding: 0 10px;*/
  color: #bbbbbb;
}

.com-open .sdong span {
  color: #08f;
  cursor: pointer;
}

.com-open .sdong span:hover {
  color: #d00;
  text-decoration: underline;
}

.com-open .nqih {
  margin-left: 10px;
  color: #d00;
}

.com-open .sum {
  margin-left: 5px;
  color: blue;
  font-weight: bold;
}

.com-open .result {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.com-open .result li {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.com-open .result li.ballsjia p {
  width: auto;
  background: none;
  color: blue;
}

.com-open .result li.ballsjia span {
  display: none;
}

.com-open .result li p {
  width: 21px;
  height: 21px;
  background-image: url(data:image/gif;base64,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);
  background-repeat: no-repeat;
  background-size: 100% 100%;
  font-style: normal;
  font-weight: bold;
  color: White;
  line-height: 21px;
  text-align: center;
  border-radius: 100%;
  text-indent: -100px;
  overflow: hidden;
}

.com-open .result li p.blueball,
.com-open .result li p.greenball,
.com-open .result li p.redball {
  background-image: url(data:image/gif;base64,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);
  background-size: inherit;
  text-indent: 0;
}

.com-open .result li p.redball {
  background-position: 0 0;
  background-color: #fb0200;
}

.com-open .result li p.redball + span {
  color: #fb0200;
}

.com-open .result li p.blueball {
  background-position: -42px 0;
  background-color: #0000fe;
}

.com-open .result li p.blueball + span {
  color: #0000fe;
}

.com-open .result li p.greenball {
  background-position: -21px 0;
  background-color: #007f04;
}

.com-open .result li p.greenball + span {
  color: #007f04;
}

.com-open .result li span {
  height: 21px;
  line-height: 21px;
  margin: 0 5px 0 2px;
}
</style>
<style>
@charset "UTF-8";

/* 默认样式 */
.home {
  overflow: hidden;
  background: #f1f2f6;
}

.home .top {
  position: fixed;
  left: 0;
  top: 0;
  right: 0;
  z-index: 100;
  height: 30px;
  background: #ffffff;
  -webkit-box-shadow: 0 0 5px #cccccc;
  box-shadow: 0 0 5px #cccccc;
}

.home .top .content {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  height: 100%;
}

.home .top .content .welcome {
  font-size: 13px;
  color: red;
  line-height: 30px;
}

.home .top .content .huang {
  color: red;
  font-size: 14px;
  cursor: pointer;
}

.home .top .content .huang em {
  color: #3894e4;
}

.home .top-padding {
  height: 30px;
}

.home .header {
  min-width: 982px;
  height: 147px;
  background: #095749;
}

.home .header .content {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  height: 100%;
}

.home .header .content .logo {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  max-width: 281px;
  max-height: 93px;
  overflow: hidden;
}

.home .header .content .logo a {
  display: inline-block;
}

.home .header .content .logo img {
  width: 100%;
  height: 100%;
}

.home .header .content .advert {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.home .header .content .advert a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.home .header .content .advert img {
  width: 100%;
  max-height: 100px;
}

.home .header .content .channel {
  width: 50%;
  height: 100%;
}

.home .header .content .channel a {
  display: inline-block;
  height: 100%;
  width: 100%;
}

.home .header .content .channel a img {
  height: 100%;
  width: 100%;
  max-height: 100%;
}

.home .header .content .rates {
  border: 1px solid #fff;
  border-radius: 2px;
  padding: 10px;
  color: #fff;
  font-size: 14px;
}

.home .header .content .rates .row {
  margin: 0px 20px 0px 10px;
}

.home .header .content .rates .row .label {
  margin-bottom: 8px;
}

.home .header .content .rates .row .label .refresh {
  color: #08f;
  cursor: pointer;
}

.home .header .content .rates .row .label .refresh:hover {
  color: #d00;
  text-decoration: underline;
}

.home .header .content .rates .row .text {
  margin-bottom: 6px;
}

.home .header .content .rates .row .site {
  margin-top: 8px;
}

.home .header .content .rates .row .site a {
  color: #fff;
  background-color: #0aa4d8;
  border-radius: 10px;
  display: inline-block;
  padding: 0px 10px;
}

.home .header .content .rates .row .site a:hover {
  color: #ffff00;
}

.home .header .content .rates .row:first-child {
  margin: 0px 10px 0px 20px;
}

.home .container {
  margin: 0 auto;
  width: 960px;
  border: 1px solid #e7e8ea;
  border-top: none;
  padding: 10px;
  background: #ffffff;
}

.home .container .out-open {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding: 0 10px;
  border: 1px solid #e7e8ea;
  height: 36px;
}

.home .container .out-open .date {
  width: 90px;
  font-size: 14px;
  white-space: nowrap;
}

.home .container .out-open .iframe {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  width: calc(100% - 90px);
}

.home .container .advert-txt {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.home .container .advert-txt span {
  position: relative;
  height: 23px;
  line-height: 23px;
  padding: 0 10px 0 7px;
  background: #da5625;
  font-size: 14px;
  color: #ffffff;
}

.home .container .advert-txt span:before {
  position: absolute;
  top: 4.5px;
  right: -13px;
  content: "";
  width: 0px;
  height: 0px;
  border: 7px solid transparent;
  border-left: 7px solid #da5625;
}

.home .container .advert-txt em {
  margin: 0 12px;
  font-size: 14px;
  font-weight: 700;
  color: #da5625;
}

.home .container .advert-txt em:hover {
  color: #333333;
}

.home .container .advert-txt a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.home .container .advert-img {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.home .container .advert-img a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.home .container .advert-img a img {
  width: 100%;
}

.home .container .list-ul .th {
  height: 38px;
  background: #065446;
  padding-left: 20px;
  line-height: 38px;
  color: #ffffff;
  font-size: 16px;
  letter-spacing: 2px;
  border-bottom: 1px solid #ffffff;
}

.home .container .list-ul ul {
  background: #2eb09e;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  -ms-flex-flow: row wrap;
  flex-flow: row wrap;
}

.home .container .list-ul ul li {
  position: relative;
  height: 49px;
  width: 12.5%;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  border-bottom: 1px solid #087d6d;
  border-right: 1px solid #087d6d;
}

.home .container .list-ul ul li:hover {
  z-index: 1;
}

.home .container .list-ul ul li:hover > div {
  display: block;
}

.home .container .list-ul ul li a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  height: 100%;
  line-height: 49px;
  color: #ffffff;
  font-size: 16px;
  text-decoration: none;
  white-space: nowrap;
  overflow: hidden;
}

.home .container .list-ul ul li a:hover {
  background: #5ec0b2;
  color: #ffff00;
}

.home .container .list-ul ul li a img {
  margin-right: 3px;
}

.home .container .list-ul ul li > div {
  display: none;
  position: absolute;
  left: 0;
  top: 49px;
  right: 0;
  z-index: 2;
}

.home .container .list-ul ul li > div a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  background: #087d6d;
  height: 36px;
  line-height: 36px;
  border-bottom: 1px solid #1ca18f;
  white-space: nowrap;
  font-size: 14px;
  overflow: hidden;
}

.home .container .list-ul ul li > div a:hover {
  background: #1ca18f;
}

.home .container .list-ul ul li > div > div {
  position: relative;
}

.home .container .list-ul ul li > div > div:hover a {
  background: #1ca18f;
}

.home .container .list-ul ul li > div > div:hover > div {
  display: block;
}

.home .container .list-ul ul li > div > div > div {
  display: none;
  position: absolute;
  top: 0;
  right: -100%;
  width: 100%;
}

.home .container .list-ul ul li > div > div > div a {
  background: #1ca18f;
  border-bottom: 1px solid #087d6d;
}

.home .container .links {
  border: 1px solid #2eb09e;
}

.home .container .links .th {
  padding: 0 20px;
  height: 38px;
  background: #2eb09e;
  line-height: 38px;
  font-size: 16px;
  font-weight: 600;
  color: #ffffff;
}

.home .container .links .td {
  padding: 5px 10px 10px 0;
}

.home .container .links .td a {
  display: inline-block;
  margin: 5px 0 0 10px;
  color: #2eb09e;
  text-decoration: none;
  font-size: 14px;
}

.home .footer {
  width: 100%;
  padding: 10px 0;
  margin: 0 auto;
}

.home .footer .qq {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  height: 28px;
}

.home .footer .qq span {
  margin-right: 10px;
  font-size: 16px;
  color: #da5625;
  font-weight: 100;
}

.home .footer p {
  line-height: 28px;
  text-align: center;
  color: #666666;
}

.home .footer p.col {
  color: #2eb09e;
}

.home .kefu {
  position: fixed;
  top: 179px;
  right: 0;
}

.home .kefu .btn {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 40px;
  height: 160px;
  background: #70ca5d;
  border-radius: 4px 0 0 4px;
  cursor: pointer;
}

.home .kefu .btn b {
  width: 26px;
  height: 132px;
  background: url(/public/img/kefu.png) no-repeat 0 0;
}

.home .kefu .btn b:hover {
  background-position: -26px 0;
}

.home .kefu .mos {
  position: absolute;
  top: 0;
  z-index: 1;
  border: 1px solid #62b651;
  background: #f4f7fa;
  border-radius: 4px;
  width: 122px;
  min-height: 140px;
  padding: 12px 10px;
  -webkit-transition: 0.3s cubic-bezier(0.19, 1, 0.22, 1);
  transition: 0.3s cubic-bezier(0.19, 1, 0.22, 1);
}

.home .kefu .mos .tit {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  height: 18px;
  padding-bottom: 10px;
  border-bottom: 1px dashed #d1d4cc;
}

.home .kefu .mos .tit b {
  height: 18px;
  width: 90px;
  background: url(/public/img/kefu.png) no-repeat -52px 0;
}

.home .kefu .mos .tit em {
  height: 18px;
  width: 18px;
  background: url(/public/img/kefu.png) no-repeat -105px -114px;
  cursor: pointer;
}

.home .kefu .mos .tit em:hover {
  background-position: -124px -114px;
}

.home .kefu .mos ul li {
  margin-top: 6px;
}

.home .kefu .mos ul li a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-pack: distribute;
  justify-content: space-around;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding: 6px 12px;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  color: #697466;
  text-decoration: none;
  font-size: 14px;
}

.home .kefu .mos ul li a:hover {
  background: #eaebe9;
  border-radius: 4px;
}

.home .kefu .mos ul li a span {
  width: 30px;
  height: 22px;
  background: url(/public/img/kefu.png) no-repeat -112px -19px;
  line-height: 16px;
  text-align: center;
  font-size: 12px;
  color: #ffffff;
}

@media screen and (max-device-width: 768px) {
  .home .header .content .rates {
    margin-right: 10px;
  }
}

/*** 模板样式 ***/
/* start 蓝色模板样式 */
.blue .home .header {
  background: #4b58a0;
}

.blue .home .header .content .rates {
  color: #fff;
}

.blue .home .header .content .rates .row {
  margin-left: 40px;
}

.blue .home .header .content .rates .row .label {
  margin-bottom: 6px;
}

.blue .home .header .content .rates .row .label .refresh {
  color: #03a9f4;
  cursor: pointer;
}

.blue .home .header .content .rates .row .label .refresh:hover {
  color: #ffff00;
  text-decoration: underline;
}

.blue .home .header .content .rates .row .text {
  margin-bottom: 6px;
}

.blue .home .list-ul .th {
  background: #4b58a0;
}

.blue .home .list-ul ul {
  background: #4b58a0;
}

.blue .home .container .list-ul .th {
  background: #4b58a0;
}

.blue .home .container .list-ul ul {
  background: #2196f3;
}

.blue .home .container .list-ul ul li {
  position: relative;
  height: 49px;
  width: 12.5%;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  border-bottom: 1px solid #24bfe3;
  border-right: 1px solid #24bfe3;
}

.blue .home .container .list-ul ul li:hover {
  z-index: 1;
}

.blue .home .container .list-ul ul li:hover > div {
  display: block;
}

.blue .home .container .list-ul ul li a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  height: 100%;
  line-height: 49px;
  color: #ffffff;
  font-size: 16px;
  text-decoration: none;
  white-space: nowrap;
  overflow: hidden;
}

.blue .home .container .list-ul ul li a:hover {
  background: #2196f3;
  color: #ffff00;
}

.blue .home .container .list-ul ul li a img {
  margin-right: 3px;
}

.blue .home .container .list-ul ul li > div {
  display: none;
  position: absolute;
  left: 0;
  top: 49px;
  right: 0;
  z-index: 2;
}

.blue .home .container .list-ul ul li > div a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  background: #2778ed;
  height: 36px;
  line-height: 36px;
  border-bottom: 1px solid #24bfe3;
  white-space: nowrap;
  font-size: 14px;
  overflow: hidden;
}

.blue .home .container .list-ul ul li > div a:hover {
  background: #2185f3;
}

.blue .home .container .list-ul ul li > div > div {
  position: relative;
}

.blue .home .container .list-ul ul li > div > div:hover a {
  background: #2185f3;
}

.blue .home .container .list-ul ul li > div > div:hover > div {
  display: block;
}

.blue .home .container .list-ul ul li > div > div > div {
  display: none;
  position: absolute;
  top: 0;
  right: -100%;
  width: 100%;
}

.blue .home .container .list-ul ul li > div > div > div a {
  background: #2778ed;
  border-bottom: 1px solid #24bfe3;
}

.blue .home .container .links {
  border: 1px solid #4b58a0;
}

.blue .home .container .links .th {
  background: #4b58a0;
}

.blue .home .container .links .td a {
  color: #29b7f4;
}

.blue .home .footer p.col {
  color: #29b7f4;
}

.blue .bonus .b-content {
  border: 1px solid #4b58a0;
  -webkit-box-shadow: 0 0 12px #4b58a0;
  box-shadow: 0 0 12px #4b58a0;
}

.blue .bonus .b-content .b-title {
  background: #4b58a0 !important;
}

/* end 蓝色模板 */
/* start 红色模板样式 */
.red .home .header {
  background: #ff2616;
}

.red .home .header .content .rates {
  color: #fff;
}

.red .home .header .content .rates .row {
  margin-left: 30px;
}

.red .home .header .content .rates .row .label {
  margin-bottom: 6px;
}

.red .home .header .content .rates .row .label .refresh {
  color: #03a9f4;
  cursor: pointer;
}

.red .home .header .content .rates .row .label .refresh:hover {
  color: #ffff00;
  text-decoration: underline;
}

.red .home .header .content .rates .row .text {
  margin-bottom: 6px;
}

.red .home .header .content .rates .row .site a {
  background-color: #ff8f3d;
}

.red .home .list-ul .th {
  background: #ff2616;
}

.red .home .list-ul ul {
  background: #ff2616;
}

.red .home .container .list-ul .th {
  background: #ff2616;
}

.red .home .container .list-ul ul {
  background: #ff903d;
}

.red .home .container .list-ul ul li {
  position: relative;
  height: 49px;
  width: 12.5%;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  border-bottom: 1px solid #ffa63a;
  border-right: 1px solid #ffa63a;
}

.red .home .container .list-ul ul li:hover {
  z-index: 1;
}

.red .home .container .list-ul ul li:hover > div {
  display: block;
}

.red .home .container .list-ul ul li a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  height: 100%;
  line-height: 49px;
  color: #ffffff;
  font-size: 16px;
  text-decoration: none;
  white-space: nowrap;
  overflow: hidden;
}

.red .home .container .list-ul ul li a:hover {
  background: #ff733a;
  color: #ffff00;
}

.red .home .container .list-ul ul li a img {
  margin-right: 3px;
}

.red .home .container .list-ul ul li > div {
  display: none;
  position: absolute;
  left: 0;
  top: 49px;
  right: 0;
  z-index: 2;
}

.red .home .container .list-ul ul li > div a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  background: #ff733a;
  height: 36px;
  line-height: 36px;
  border-bottom: 1px solid #ffa63a;
  white-space: nowrap;
  font-size: 14px;
  overflow: hidden;
}

.red .home .container .list-ul ul li > div a:hover {
  background: #ff733a;
}

.red .home .container .list-ul ul li > div > div {
  position: relative;
}

.red .home .container .list-ul ul li > div > div:hover a {
  background: #ff733a;
}

.red .home .container .list-ul ul li > div > div:hover > div {
  display: block;
}

.red .home .container .list-ul ul li > div > div > div {
  display: none;
  position: absolute;
  top: 0;
  right: -100%;
  width: 100%;
}

.red .home .container .list-ul ul li > div > div > div a {
  background: #ff733a;
  border-bottom: 1px solid #ffa63a;
}

.red .home .container .links {
  border: 1px solid #ff733a;
}

.red .home .container .links .th {
  background: #ff733a;
}

.red .home .container .links .td a {
  color: #ff733a;
}

.red .home .footer p.col {
  color: #ff733a;
}

.red .bonus .b-content {
  border: 1px solid #ff2616;
  -webkit-box-shadow: 0 0 12px #ff2616;
  box-shadow: 0 0 12px #ff2616;
}

.red .bonus .b-content .b-title {
  background: #ff2616 !important;
}

/* end  红色模板样式 */
/* start 橙色模板样式 */
.orange .home .header {
  background: #ff7816;
}

.orange .home .header .content .rates {
  color: #fff;
}

.orange .home .header .content .rates .row {
  margin-left: 30px;
}

.orange .home .header .content .rates .row .label {
  margin-bottom: 6px;
}

.orange .home .header .content .rates .row .label .refresh {
  color: #08f;
  cursor: pointer;
}

.orange .home .header .content .rates .row .label .refresh:hover {
  color: #ffff00;
  text-decoration: underline;
}

.orange .home .header .content .rates .row .text {
  margin-bottom: 6px;
}

.orange .home .list-ul .th {
  background: #ff7816;
}

.orange .home .list-ul ul {
  background: #ff7816;
}

.orange .home .container .list-ul .th {
  background: #ff7816;
}

.orange .home .container .list-ul ul {
  background: #f3c75f;
}

.orange .home .container .list-ul ul li {
  position: relative;
  height: 49px;
  width: 12.5%;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  border-bottom: 1px solid #ff7816;
  border-right: 1px solid #ff7816;
}

.orange .home .container .list-ul ul li:hover {
  z-index: 1;
}

.orange .home .container .list-ul ul li:hover > div {
  display: block;
}

.orange .home .container .list-ul ul li a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  height: 100%;
  line-height: 49px;
  color: #ffffff;
  font-size: 16px;
  text-decoration: none;
  white-space: nowrap;
  overflow: hidden;
}

.orange .home .container .list-ul ul li a:hover {
  background: #ff7816;
  color: #ffff00;
}

.orange .home .container .list-ul ul li a img {
  margin-right: 3px;
}

.orange .home .container .list-ul ul li > div {
  display: none;
  position: absolute;
  left: 0;
  top: 49px;
  right: 0;
  z-index: 2;
}

.orange .home .container .list-ul ul li > div a {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  background: #ff7816;
  height: 36px;
  line-height: 36px;
  border-bottom: 1px solid #f3c75f;
  white-space: nowrap;
  font-size: 14px;
  overflow: hidden;
}

.orange .home .container .list-ul ul li > div a:hover {
  background: #ff7816;
}

.orange .home .container .list-ul ul li > div > div {
  position: relative;
}

.orange .home .container .list-ul ul li > div > div:hover a {
  background: #ff733a;
}

.orange .home .container .list-ul ul li > div > div:hover > div {
  display: block;
}

.orange .home .container .list-ul ul li > div > div > div {
  display: none;
  position: absolute;
  top: 0;
  right: -100%;
  width: 100%;
}

.orange .home .container .list-ul ul li > div > div > div a {
  background: #ff733a;
  border-bottom: 1px solid #ffa63a;
}

.orange .home .container .links {
  border: 1px solid #ff733a;
}

.orange .home .container .links .th {
  background: #ff7816;
}

.orange .home .container .links .td a {
  color: #ff7816;
}

.orange .home .footer {
  background: #ff7816;
}

.orange .home .footer p {
  color: #f3c75f;
}

.orange .home .footer p.col {
  color: #fff;
}

.orange .bonus .b-content {
  border: 1px solid #ff7816;
  -webkit-box-shadow: 0 0 12px #ff7816;
  box-shadow: 0 0 12px #ff7816;
}

.orange .bonus .b-content .b-title {
  background: #ff7816 !important;
}

/* end  橙色模板样式 */
</style>
