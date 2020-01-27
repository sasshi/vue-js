<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>IndexedDB</title>
</head>
<body>
    <div id="player">
        <h2>xxxx</h2>
        <p>睡眠時間：xx分</p>
        <p>残り時間：<span id="rest">xxx</span>秒</p>
        <!-- ブラウザストレージの場合は読み込みが完了するまでボタン表示させない -->
        <button id="start">START</button>
        <button id="stop">STOP</button>
        <button id="interruption">INTERRUPTION</button>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
<script type="text/javascript" src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="https://www.promisejs.org/polyfills/promise-6.1.0.min.js"></script><!-- IE11でaxiosを使う為 -->
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script><!-- IE11でPromiseを使う為 -->

<script>

// https://codepen.io/edward1995/pen/gXEPPa
new Vue({
    el: '#player',
    data: {
        db: {
            obj: {},
            name: 'testest',
            version: 1,
            store_name: 'music'
        },
        music: {
            title: '',
            guide: '',
            minute: ''
        }
    },
    methods: {
        /**
         * データベースを設定する
         */
        openDb: function() {
            return new Promise(function(resolve, reject) {
                var req = indexedDB.open(this.db.name, this.db.version);
                console.log(1);
                // データベースを作成する
                console.log(2);
                req.onupgradeneeded = function(evt) {
                    var store = evt.currentTarget.result.createObjectStore(this.db.store_name, { keyPath: 'music_no' });
                    store.createIndex("file", "file", { unique: false });
                    store.createIndex("created_at", "created_at", { unique: false });
                }.bind(this); // function内でthisを使用可能にするためbindする
                
                // データベース作成成功時はIDBDatabaseインスタンスを格納
                req.onsuccess = function(evt) {
                    console.log(3);
                    console.log('onsuccess');
                    console.log(resolve);
                    this.db.obj = evt.target.result;
                    resolve(); // resolve()を唱えて終了
                }.bind(this); // function内でthisを使用可能にするためbindする
                
                req.onerror = function(evt) {
                    console.log("openDb:", evt.target.error.code);
                };
                console.log(4);
                reject(new Error('error'));
                console.log(5);
            }.bind(this));

            // return promise.then(function() {
            //     return new Promise();
            // });

            // var req = indexedDB.open(this.db.name, this.db.version);

            // // データベースを作成する
            // req.onupgradeneeded = function(evt) {
            //     var store = evt.currentTarget.result.createObjectStore(this.db.store_name, { keyPath: 'music_no' });
            //     store.createIndex("file", "file", { unique: false });
            //     store.createIndex("created_at", "created_at", { unique: false });
            // }.bind(this); // function内でthisを使用可能にするためbindする
            
            // // データベース作成成功時はIDBDatabaseインスタンスを格納
            // req.onsuccess = function(evt) {
            //     console.log('onsuccess');
            //     this.db.obj = evt.target.result;
            // }.bind(this); // function内でthisを使用可能にするためbindする
            
            // req.onerror = function(evt) {
            //     console.log("openDb:", evt.target.error.code);
            // };
        },
        setMusic: function() {
            return new Promise(function(resolve) {
                console.log(resolve);
                console.log('setMusiccc');
                resolve();
            });
        }
    },
    created: function() {
        if(window.indexedDB) {
            // indexedDB対応
            this.openDb().then(this.setMusic());
            // this.openDb().then(function(){console.log('aa')});
            // var result = new Promise(function(resolve) { 
            //     this.openDb(function() {
            //     // this.setMusic('blob');
            //     });
            // }.bind(this));
            // console.log(result);
            // result.then(function() {
            //     this.setMusic('blob');
            // });
        };
        // // 通信がcacheされないようにData.now()を入れる
        // axios.get('./lib/php/functions.php?_=' + Date.now())
        // .then(function(res) {
        //     if(!res.data) {
        //         this.isSecond = res.data
        //     }
        //     console.log(res.data);
        // }
        // // axios内部でthisを使用可能にするためbindする
        // .bind(this))
        // .catch(function(err) {
        //     alert('err')
        // });
    }
})
</script>
</html>