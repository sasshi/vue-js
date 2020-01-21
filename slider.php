<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Carousel with Vue</title>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
</head>
<body>
<div id="main">
  <div id="vue-carousel" class="vue-carousel">

    <transition :name="transition_name">
      <div class="vue-carousel_body"
        v-for="(content, index) in contents"
        :key="index"
        v-if="visible_content == index"
        :style="{backgroundColor: content.bg_color}">
        {{ content.title }}
      </div>
    </transition>

    <div class="vue-carousel_footer">
      <button @click="back()" :disabled="visible_content == 0">PREV</button>
      <div class="vue-carousel_footer_dot"
        v-for="(contents, index) in contents"
        :key="index"
        :class="{'is-visible' : visible_content == index}"></div>
      <button @click="next()" :disabled="visible_content == contents.length - 1">NEXT</button>
    </div>

  </div><!-- #vue-carousel -->
</div><!-- #main -->
</style>
<script>
// 参考：https://qiita.com/Wave7KN/items/5a18c9a6ed7d6fac940f
// トランジション：https://medium.com/nyle-engineering-blog/vue-js%E3%81%AE%E3%83%88%E3%83%A9%E3%83%B3%E3%82%B8%E3%82%B7%E3%83%A7%E3%83%B3%E3%81%A8css%E3%81%A7%E4%BD%9C%E3%82%8B%E3%82%A2%E3%83%8B%E3%83%A1%E3%83%BC%E3%82%B7%E3%83%A7%E3%83%B3%E3%81%AE%E5%9F%BA%E6%9C%AC%E3%82%92%E3%82%B5%E3%83%B3%E3%83%97%E3%83%AB%E3%81%A7%E3%82%8F%E3%81%8B%E3%82%8A%E3%82%84%E3%81%99%E3%81%8F%E8%A7%A3%E8%AA%AC-d594a263497d
new Vue({
  el: '#vue-carousel',
  data: {
    contents: [{
    	title: 'Content 1',
      bg_color: '#7bbff9',
    },{
    	title: 'Content 2',
      bg_color: '#f16972',
    },{
    	title: 'Content 3',
      bg_color: '#20d2a3',
    }],
    transition_name: 'show-next',
    visible_content: 0,
  },
  methods: {
  	back() {
      this.transition_name = 'show-prev';
      this.visible_content--;
    },
    next() {
      this.transition_name = 'show-next';
      this.visible_content++;
    },
  },
})
</script>
<style>
.vue-carousel {
  position: absolute; /* 絶対指定 */
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto; /* 中央寄せ */
  height: 200px;
  width: 300px; 
  overflow: hidden; }
  .vue-carousel_body {
    color: #fff;
    height: 150px;
    left: 0;
    line-height: 150px;
    position: absolute;
    text-align: center;
    top: 0;
    width: 100%; }
  .vue-carousel_footer {
    align-items: center;
    display: flex;
    height: 50px;
    justify-content: space-between;
    position: absolute;
    top: 150px;
    width: 100%; }
    .vue-carousel_footer_dot {
      background-color: #abc2ce;
      border-radius: 50%;
      height: 6px;
      width: 6px; }
      .vue-carousel_footer_dot.is-visible {
        background-color: #7b94f9; }

.show-next-enter-active, .show-next-leave-active,
.show-prev-enter-active, .show-prev-leave-active {
  transition: all .4s; }

.show-next-enter, .show-prev-leave-to {
  transform: translateX(100%); }

.show-next-leave-to, .show-prev-enter {
  transform: translateX(-100%); }
</style>
</body>
</html>