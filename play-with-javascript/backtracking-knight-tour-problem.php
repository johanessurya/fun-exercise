<!DOCTYPE html>
<html>
<head>
    <title>Backtracking - Knight Tour Problem</title>
    <script src="https://unpkg.com/vue@3.1.5/dist/vue.global.prod.js"></script>
    <script src="../bower_components/underscore/underscore-min.js"></script>
    <script>
/* Menyelesaikan "Knight's Tour Problem" dengan javascript
 *
 * Kali ini menggunakan library VueJS untuk mempermudah manipulasi DOM
 * untuk animasi.
 */

// Tunggu sampai semua sudah diload dengan baik
window.onload = function() {
    main();
}

function main() {
    // Main App
    // Semua akan masuk disini
    var mainApp = {
        data: function() {
            return {
                // Posisi awal kuda berada
                START_POS: [0,0],
                // Untuk membantu mengetahui posisi kuda
                counter: 1,
                // Posisi saat ini
                currPos: [],
                // Pergerakan kuda. Kuda dalam catur selalu membentuk L
                // Total ada 8 pergerakan
                moveList: [
                    // Kanan atas
                    [-2, 1],
                    [-1, 2],

                    // Kanan bawah
                    [1, 2],
                    [2, 1],

                    // Kiri bawah
                    [2, -1],
                    [1, -2],

                    // Kiri atas
                    [-1, -2],
                    [-2, -1]
                ],
                // Setiap langkah akan disimpan disini
                // Ini nantinya akan digunakan untuk animasi
                replayList: [],
                // Papan catur untuk kebutuhan animasi
                replayBoard: [],
                // Menandakan apakah sudah selesai
                // dalam pencarian solusi Knight's Tour
                isCompleted: false,
                // Board untuk mencari solusi
                board: [],
                // Lebar papan catur
                // Diset "5" karena browser kadang-kadang
                // stuck jika diisi 8(papan catur normal)
                width: 5,
            }
        },
        // Method ini akan dipanggil saat vue pertama kali diload
        created: function() {
            var vm = this;
            var width = vm.width;
            var row = [];

            // Mengisi board dengan angka 0 semua
            vm.board = [];
            for(var i = 0; i < width; i++) {
                row = [];
                for(var j = 0; j < width; j++) {
                    row.push(0);
                }
                vm.board.push(row);
            }

            // Mengisi board dengan angka 0 semua
            vm.replayBoard = [];
            for(var i = 0; i < width; i++) {
                row = [];
                for(var j = 0; j < width; j++) {
                    row.push(0);
                }
                vm.replayBoard.push(row);
            }

            // Meletakkan kuda di sesuai dengan
            // START_POS
            vm.currPos = vm.START_POS;
            // Mengisi papan catur dengan jumlah langkah
            // ke-N
            vm.board[vm.currPos[0]][vm.currPos[1]] = 1;
            // Menyimpan setiap langkah yang sudah dilakukan
            vm.replayList.push({
                counter: 1,
                backtrack: false,
                position: [vm.currPos[0], vm.currPos[1]]
            });

            // Menjalankan function
            // supaya async, sehingga browser
            // tidak stuck/hang
            setTimeout(function() {
                vm.loop(0)
                vm.replay(0);
            }, 0);
        },
        methods: {
            // Method loop utama
            loop: function(moved) {
                var vm = this;
                var hasSolution = true;

                // Coba langkah "moved"
                // "hasSolution" akan bernilai true jika
                // kuda berhasil berjalan diposisi yang dituju
                hasSolution = vm.play(vm.currPos, moved, 2);
                moved++;

                // Jika solusi tidak ditemukan
                // dan masih ada langkah kuda berikutnya
                // coba lakukan langkah berikutnya
                if(!hasSolution && moved < vm.moveList.length) {
                    vm.loop(moved);
                }

                // Jika jumlah counter sudah >= dengan luas
                // papan catur, maka artinya solusi sudah ditemukan
                if (vm.counter >= this.width * this.width) {
                    vm.isCompleted = true;
                }
            },
            // Mencoba langkah dari posisi semula
            // dengan "movedList" yang ada
            play: function(currPos, moved, counter) {
                var vm = this;

                // Ini adalah nilai yang akan di return
                var temp = true;
                // Langkah berikutnya
                // Berisi koordinat/index array
                var nextMove = [];
                // Nilai pada board
                // Untuk pengecheckan apakah pada
                // papan catur nilainya sesuai atau tidak
                var nextMoveBoard = 1;
                // Untuk pengecheckan apakah
                // kuda diluar papan catur
                var isInBoard = true;

                // Hitung koordinat berikutnya
                // Berdasarkan posisi saat ini dan "moveList"
                // yang dipilih
                nextMove = this.getNextMove(currPos, vm.moveList[moved]);
                // Check, apakah koordinat "nextMove"
                // masih dalam papan catur
                isInBoard = vm.isInBoard(nextMove);

                // Jika masih dalam papan catur
                if(isInBoard)
                    // Ambil nilai pada koordinat tersebut
                    nextMoveBoard = vm.board[nextMove[0]][nextMove[1]];

                // Apakah nilai kotak tersebut masih NOL?
                if(nextMoveBoard === 0) {
                    // Update counter
                    vm.counter = counter;
                    // Update papan catur dengan mengisikan "counter"
                    // pada kotak papan catur
                    vm.board[nextMove[0]][nextMove[1]] = counter;
                    // Tambahkan langkah tersebut untuk animasi
                    vm.replayList.push({
                        counter: counter,
                        backtrack: false,
                        position: [nextMove[0], nextMove[1]]
                    });

                    // Jika counter masih kurang dari
                    // max kotak papan catur, artinya
                    // masih perlu dilanjutkan
                    // Pencarian belum usai
                    if (counter < vm.width * vm.width) {
                        // Langkah kuda, dimulai dari 0
                        var moveCounter = 0;
                        // Untuk pengecheckan solusi
                        var hasSolution = true;

                        do {
                            // Coba lakukan langkah berikutnya
                            hasSolution = vm.play(nextMove, moveCounter, counter + 1);
                            moveCounter++;

                            // JIka tidak ada solusi coba langkah selanjutnya
                        } while(!hasSolution && moveCounter < vm.moveList.length);

                        // Jika masih tidak ada solusi dan langkah sudah habis
                        // Lakukan BACKTRACK
                        if (!hasSolution && moveCounter >= vm.moveList.length) {
                            // Set langkah ini menjadi NOL
                            vm.board[nextMove[0]][nextMove[1]] = 0;
                            // Return "false"
                            temp = false;
                            // Simpan langkah untuk animasi
                            vm.replayList.push({
                                counter: counter,
                                backtrack: true,
                                position: [nextMove[0], nextMove[1]]
                            });
                        }
                    }
                } else {
                    // Tidak ada solusi
                    temp = false;
                }

                return temp;
            },
            // Pengecheckan apakah posisi masih di area papan catur
            isInBoard: function(position) {
                return position[0] >= 0 && position[0] < this.width &&
                  position[1] >= 0 && position[1] < this.width;
            },
            // Hitung koordinat berikutnya berdasarkan
            // posisi saat ini dan langkah kuda yang dipilih
            getNextMove: function(currPos, moved) {
                var temp = [];
                temp[0] = currPos[0] + moved[0];
                temp[1] = currPos[1] + moved[1];

                return temp;
            },
            // Untuk keperluan animasi
            replay: function(counter) {
                var vm = this;
                var x = 0;

                // Jika sudah selesai pencarian solusinya
                // Maka lakukan animasi
                if (vm.isCompleted) {
                    x = vm.replayList[counter];
                    // debugger;

                    if (!x.backtrack) {
                        vm.replayBoard[x.position[0]][x.position[1]] = x.counter;
                    } else {
                        vm.replayBoard[x.position[0]][x.position[1]] = 0;
                    }
                }

                // Jika counter masih kurang dari
                // jumlah replayList
                if (counter < vm.replayList.length) {
                    setTimeout(function() {
                      vm.replay(counter + 1);
                    }, 500);
                }
            }
        }
    };

    Vue.createApp(mainApp).mount('#app');
}
    </script>
<?php include '../header.php'; ?>
    <style>
td {
    width: 70px;
    height: 70px;
    text-align: center;
    font-size: 40px;
}
    </style>
</head>
<body>
    <div id="app">
        <h1>Knight Tour Problem</h1>
        <table border="1">
            <tr v-for="x in replayBoard">
                <td v-for="y in x">{{ y == 0 ? ' ': y }}</td>
            </tr>
        </table>
    </div>
    <canvas id="mycanvas" width="600" height="600"></canvas>
</body>
</html>