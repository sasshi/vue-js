<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>IndexedDB</title>
</head>
<body>
    <div id="app">
        <div id="player">
            <h2>xxxx</h2>
            <p>睡眠時間：xx分</p>
            <p>残り時間：<span id="rest">{{floorRemainingTime}}</span>秒</p>
            <!-- ブラウザストレージの場合は読み込みが完了するまでボタン表示させない -->
            <div v-if="audio">
                <button id="play" @click='playMusic()'>PLAY</button>
                <button id="stop" @click="pauseMusic()">STOP</button>
                <button id="interruption">INTERRUPTION</button>
            </div>
        </div>
        <setting-component v-on:child-event="childSettingSave" v-bind:music_no="music_no"></setting-component>
    </div>
</body>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
<script type="text/javascript" src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="https://www.promisejs.org/polyfills/promise-6.1.0.min.js"></script><!-- IE11でaxiosを使う為 -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script><!-- IE11でPromiseを使う為 -->

<script>
var configComponent = {
    data: function () {
        return {
            // v-forで回すため配列（[]で囲む）にする（{}はオブジェクト）
            minutes: [
                { label: '15分', value: 1 },
                { label: '20分', value: 2 },
                { label: '25分', value: 3 },
                { label: '30分', value: 4 }
            ],
            guides: [
                { label: 'off', value: 10 },
                { label: 'on', value: 20 }
            ],
            titles: [
                { label: '海辺の朝', value: 100 },
                { label: '森林でのひととき', value: 200 },
                { label: '雨の午後', value: 300 },
                { label: '街の音', value: 400 },
                { label: '小川のせせらぎ', value: 500 },
                { label: '静寂の時間', value: 600 }
            ],
            selected: {
                minute: 1,
                guide: 10,
                title: 100,
            },
        }
    }
};

var settingComponent = {
    mixins: [configComponent],
    // 親コンポーネントからの受取データ
    props: ['music_no'],
    // コンポーネントはdataをfunction形式にする
    data: function () {
        return {
            show: false
        }
    },
    methods: {
        /**
         * 設定情報を親コンポーネントに渡す
         */
        save: function() {
            this.$emit('child-event', this.selected);
        },
        /**
         * 設定を初期値に戻す
         */
        reset: function() {
            // 第一引数：設定先、第二引数：設定基データ
            Object.assign(this.$data.selected, config.data().selected);
        }
    },
    created: function() {
        /**
         * 親コンポーネントのmusic_noを基にdataオブジェクトに設定する
         */
        var str = String(this.music_no);
        this.$data.selected.minute = Number(str.slice(2, 3));
        this.$data.selected.guide = Number(str.slice(1, 2))*10;
        this.$data.selected.title = Number(str.slice(0, 1))*100;
    },
    template:
    `<div id="setting">
        <button id="setting_ctl" @click="show=!show">設定を開く</button>
        <div v-show="show">
            <h3>睡眠時間</h3>
            <div id="minutes">
                <ul>
                    <li v-for="minute in minutes">
                        <label v-bind:for="'minute_'+minute.value"><input type="radio" v-bind:value="minute.value" v-bind:id="'minute_'+minute.value" v-model="selected.minute">{{minute.label}}</label>
                    </li>
                </ul>
            </div>
            <h3>音声ガイダンス</h3>
            <div id="guides">
                <ul>
                    <li v-for="guide in guides">
                        <label v-bind:for="'guide_'+guide.value"><input type="radio" v-bind:value="guide.value" v-bind:id="'guide_'+guide.value" v-model="selected.guide">{{guide.label}}</label>
                    </li>
                </ul>
            </div>
            <h3>曲名</h3>
            <div id="titles">
                <ul>
                    <li v-for="title in titles">
                        <label v-bind:for="'title_'+title.value"><input type="radio" v-bind:value="title.value" v-bind:id="'title_'+title.value" v-model="selected.title">{{title.label}}</label>
                    </li>
                </ul>
            </div>
            <button type="button" id="save" @click="save">save</button>
            <button type="button" id="reset" @click="reset">reset</button>
        </div>
    </div>`
    // IE11ではES2015のテンプレートリテラルが未サポートの為、上記コードを1列で表記した
    // '<div id="setting"><button id="setting_ctl" @click="show=!show">設定を開く</button><div v-show="show"><h3>睡眠時間</h3><div id="minutes"><ul><li v-for="minute in minutes"><label v-bind:for="minute.id"><input type="radio" v-bind:value="minute.value" v-bind:id="minute.id" v-model="selected.minute">{{minute.label}}</label></li></ul></div><h3>音声ガイダンス</h3><div id="guides"><ul><li v-for="guide in guides"><label v-bind:for="guide.id"><input type="radio" v-bind:value="guide.value" v-bind:id="guide.id" v-model="selected.guide">{{guide.label}}</label></li></ul></div><h3>曲名</h3><div id="titles"><ul><li v-for="title in titles"><label v-bind:for="title.id"><input type="radio" v-bind:value="title.value" v-bind:id="title.id" v-model="selected.title">{{title.label}}</label></li></ul></div><button type="button" id="save" @click="sendParent">save</button><button type="button" id="reset" @click="reset">reset</button></div></div>'
};
// https://codepen.io/edward1995/pen/gXEPPa
// もしかしてaudioタグを使わない方が良いのかもしれない。。https://matthiashager.com/tardis/vuejs-media-player-tutorial
var app = {
    mixins: [configComponent],
    el: '#app',
    components: {
        'setting-component': settingComponent
    },
    data: {
        db: {
            obj: '',
            name: 'testest',
            version: 1,
            store: {
                name: 'music',
            }
        },
        music_no: 111,
        audio: {
            obj: '',
            remaining_time: 0, // 残り時間
            interval: ''
        }
    },
    computed: {
        /**
         * 残り時間の小数点を切り捨てる
         */
        floorRemainingTime: function() {
            return Math.floor(this.audio.remaining_time);
        }
    },
    watch: {
        /**
         * データベース呼び出し後の処理
         */
        'db.obj': function(val) { // val変数にはdb.objが入っている（TODO:使うのかどうかは検討する）
            this.setMusic();
        },
        /**
         * 
         */
        'music_no': function(val) {
            this.saveMusic();
        }
    },
    methods: {
        /**
         * 設定コンポーネントから受取
         */
        childSettingSave: function(minute) {
            alert(minute);
            console.log(minute);
        },
        /**
         * 音楽を再生
         */
        playMusic: function() {
            if(this.audio.obj.readyState !== 4) {
                // 再生可能状態でなければ再生可能状態になった時のイベント通知をセットします
                this.audio.obj.oncanplaythrough = function(evt) {
                    this.audio.obj.play();
                };
            } else {
                // 音楽を再生する
                this.audio.obj.play();
                // 残り時間を設定
                this.audio.remaining_time = this.audio.obj.duration - this.audio.obj.currentTime;
                this.audio.interval = setInterval(function() {
                    this.countMusicTime();
                }.bind(this),1000);
            }
        },
        /**
         * 音楽を停止
         */
        pauseMusic: function() {
            this.audio.obj.pause();
            clearInterval(this.audio.interval);
        },
        /**
         * 再生秒数をカウント
         */
        countMusicTime: function() {
            this.audio.remaining_time--;
        },
        /**
         * データベースの設定、呼び出し
         */
        openDb: function() {
            var req = indexedDB.open(this.db.name, this.db.version);

            // データベースを作成する
            req.onupgradeneeded = function(evt) {
                var store = evt.currentTarget.result.createObjectStore(this.db.store.name, { keyPath: 'music_no' });
                store.createIndex("file", "file", { unique: false });
                store.createIndex("created_at", "created_at", { unique: false });
            }.bind(this); // function内でthisを使用可能にするためbindする
            // データベース作成成功時はIDBDatabaseインスタンスを格納
            req.onsuccess = function(evt) {
                console.log('onsuccess');
                this.db.obj = evt.target.result;
            }.bind(this); // function内でthisを使用可能にするためbindする
            req.onerror = function(evt) {
                console.log("openDb:", evt.target.error.code);
            };
        },
        /**
         * オブジェクトストア（RDBでいうテーブル）を取得
         */
        getObjectStore: function(mode) {
            var tx = this.db.obj.transaction(this.db.store.name, mode);
            return tx.objectStore(this.db.store.name);
        },
        /**
         * オブジェクトストア（RDBでいうテーブル）を削除
         */
        clearObjectStore: function() {
            var store = this.getObjectStore('readwrite');
            var req = store.clear();
            req.onsuccess = function(evt) {
                console.log("Store cleared");
            };
            req.onerror = function (evt) {
                console.log("clearObjectStore:", evt.target);
            };
        },
        /**
         * プレイヤーに音楽を設定する
         */
        setMusic: function() {
            if(this.db.obj !== '') {
                // ブラウザストレージ対応
                var store = this.getObjectStore('readonly');
                // 主キーに該当するレコードを取得
                var req = store.get(Number(this.music_no));
                req.onsuccess = function(evt) {
                    var row = evt.target.result;
                    console.log(this.music_no);
                    if(typeof row !== 'undefined' && typeof row.file === 'object') {
                        // audioオブジェクトにBlobデータを設定
                        // 参考：https://codepen.io/djodi/pen/NXJBRp/
                        var src = URL.createObjectURL(row.file);
                    } else {
                        // データベースに音楽データが存在しないのでサーバー内の実体ファイルを保存
                        var src = './lib/music/music'+this.music_no+'.mp3?';
                    }
                    this.audio.obj = new Audio(src);
                }.bind(this); // function内でthisを使用可能にするためbindする
                req.onerror = function(evt) {
                    // 存在しない
                    console.log(evt);
                }
            } else {
                // ブラウザストレージ非対応
                $('#playersrc').attr('src', '<?php echo PATH_MUSIC_FILE.'music'; ?>'+this.current_music_no+'<?php echo '.mp3'; ?>');
                document.querySelector('#player').load();
            }
        },
        /**
         * データベースに音楽を保存する
         */
        saveMusic: function() {
            axios.get('./lib/music/music'+this.music_no+'.mp3?_=' + Date.now(), {
                responseType: 'blob',
                dataType: 'binary',
            })
            .then(function(res) {
                if(res.data) {
                    music_binary = res.data;
                    this.clearObjectStore(); // 一度全てのレコードを削除する
                    
                    var obj = { music_no: Number(this.music_no), file: music_binary, created_at: new Date() };
                    var store = this.getObjectStore('readwrite');

                    var req;
                    try {
                        req = store.add(obj);
                    } catch (e) {
                        if (e.name == 'DataCloneError')
                        displayActionFailure("This engine doesn't know how to clone a Blob, " +
                                            "use Firefox");
                        throw e;
                    }
                    req.onsuccess = function (evt) {
                        console.log("Insertion in DB successful");
                    };
                    req.onerror = function(evt) {
                        console.log(evt.message);
                    };
                }
            }.bind(this))
            .catch(function(err) {
                console.log(err);
                alert('err')
            });
        }
    },
    created: function() {
        if(window.indexedDB) {
            // indexedDB対応
            this.openDb();
        } else {
            this.setMusic();
        };
    }
};

new Vue(app);
</script>