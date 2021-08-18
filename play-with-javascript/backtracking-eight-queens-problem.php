<!DOCTYPE html>
<html>
<head>
    <title>Backtracking - Eight Queens Problem</title>
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
                moveList: [],
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
                width: 8,
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

            /*
            var x, y;
            x = 3;
            y = 3;
            vm.board[x][y] = 1;
            vm.boardBlockHorizontal(x);
            vm.boardBlockVertical(y);
            vm.boardBlockDiagonal(x, y);
            */
            // Menjalankan function
            // supaya async, sehingga browser
            // tidak stuck/hang
            setTimeout(function() {
                vm.main()
                vm.replay(0);
            }, 0);
        },
        methods: {
            // Method loop utama
            main: function() {
                var vm = this;
                var hasSolution = false;
                var maxMoved = vm.width;
                var row = 0;
                var column = 0;

                while (column < maxMoved && !hasSolution) {
                    hasSolution = vm.try(row, column);
                    if ( hasSolution ) {
                        break;
                    } else {
                        vm.backtrack(row, column);
                    }
                    column++;
                }

                console.table(vm.board);
                console.log('test main');

                // Jika jumlah counter sudah >= dengan luas
                // papan catur, maka artinya solusi sudah ditemukan
                vm.isCompleted = true;
                console.log(vm.board, vm.replayList, 'test');
            },
            // Mencoba langkah dari posisi semula
            // dengan "movedList" yang ada
            try: function(row, column) {
                var vm = this;

                // Ini adalah nilai yang akan di return
                var temp = false;

                // Apakah nilai kotak tersebut masih NOL?
                if(vm.isValidMove(row, column)) {
                    vm.board[row][column] = 1;
                    vm.replayList.push({
                        counter: 1,
                        backtrack: false,
                        position: [row, column]
                    });

                    vm.boardBlockInvalidSquare(vm.board, -1);

                    if ( row + 1 < vm.width ) {
                        var hasSolution = false;
                        for ( var i = 0; i < vm.width; i++ ) {
                            hasSolution = false;
                            hasSolution = vm.try(row + 1, i);

                            if ( hasSolution ) {
                                temp = true;
                                break;
                            }
                        }

                        if ( !hasSolution ) {
                            vm.backtrack(row, column);
                            temp = false;
                        }
                    } else {
                        temp = true;
                    }
                }

                return temp;
            },
            backtrack: function(row, column) {
                var vm = this;

                vm.board[row][column] = 0;
                vm.replayList.push({
                    counter: 1,
                    backtrack: true,
                    position: [row, column]
                });

                vm.boardBlockHorizontal(vm.board, row, 0);
                vm.boardBlockVertical(vm.board, column, 0);
                vm.boardBlockDiagonal(vm.board, row, column, 0);

                vm.boardBlockInvalidSquare(vm.board, -1);
            },
            isValidMove: function(row, column) {
                // debugger;
                var vm = this;
                var temp = true;

                if (vm.board[row][column] !== 0) {
                    temp = false;
                }

                return temp;
            },
            boardBlockInvalidSquare: function(board, value) {
                var vm = this;
                var row = 0;
                var column = 0;
                var found = {
                    row: null,
                    column: null
                }

                for (row = 0; row < vm.width; row++) {
                    found.row = null;
                    found.column = null;
                    for (column = 0; column < vm.width; column++) {
                        if (board[row][column] === 1) {
                            found.row = row;
                            found.column = column;
                            break;
                        }
                    }

                    if (found.row !== null) {
                        vm.boardBlockHorizontal(board, found.row, value);
                        vm.boardBlockVertical(board, found.column, value);
                        vm.boardBlockDiagonal(board, found.row, found.column, value);
                    }
                }
            },
            boardBlockHorizontal: function(board, row, value) {
                var vm = this;

                if (value === undefined)
                    value = -1;

                for (var i = 0; i < vm.width; i++) {
                    if (board[row][i] <= 0) {
                        board[row][i] = value;
                    }
                }
            },
            boardBlockVertical: function(board, column, value) {
                var vm = this;

                if (value === undefined)
                    value = -1;

                for (var i = 0; i < vm.width; i++) {
                    if (board[i][column] <= 0) {
                        board[i][column] = value;
                    }
                }
            },
            boardBlockDiagonal: function(board, row, column, value) {
                var vm = this;
                var startRow = 0;
                var startColumn = 0;

                if (value === undefined)
                    value = -1;

                // Block from left to right
                startRow = row;
                startColumn = column;

                // Find starting point
                while(startRow > 0 && startColumn > 0) {
                    startRow--;
                    startColumn--;
                }

                while(startRow < vm.width && startColumn < vm.width) {
                    if (board[startRow][startColumn] <= 0) {
                        board[startRow][startColumn] = value;
                    }

                    startRow++;
                    startColumn++;
                }

                // Block from right to left
                startRow = row;
                startColumn = column;
                    // debugger;
                // Find starting point
                while(startRow > 0 && startColumn < vm.width) {
                    startRow--;
                    startColumn++;
                }

                while(startRow < vm.width && startColumn >= 0) {
                    if (board[startRow][startColumn] <= 0) {
                        board[startRow][startColumn] = value;
                    }

                    startRow++;
                    startColumn--;
                }
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
                // debugger;
                var vm = this;
                var x = 0;
                var row = 0;
                var column = 0;

                // Jika sudah selesai pencarian solusinya
                // Maka lakukan animasi
                if (vm.isCompleted) {
                    x = vm.replayList[counter];
                    row = x.position[0];
                    column = x.position[1];
                    // debugger;

                    if (!x.backtrack) {
                        vm.replayBoard[row][column] = 1;

                        vm.boardBlockInvalidSquare(vm.replayBoard, -1);
                    } else {
                        vm.replayBoard[row][column] = 0;

                        vm.boardBlockHorizontal(vm.replayBoard, row, 0);
                        vm.boardBlockVertical(vm.replayBoard, column, 0);
                        vm.boardBlockDiagonal(vm.replayBoard, row, column, 0);

                        vm.boardBlockInvalidSquare(vm.replayBoard, -1);
                    }
                }

                // Jika counter masih kurang dari
                // jumlah replayList
                if (counter < vm.replayList.length) {
                    setTimeout(function() {
                      vm.replay(counter + 1);
                    }, 200);
                }
            },
            tdClass: function(y) {
                return true;
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

.green {
    background-color: green;
}

.red {
    background-color: red;
}

    </style>
</head>
<body>
    <div id="app">
        <h1>Eight Queens Problem</h1>
        <table border="1">
            <tr v-for="x in replayBoard">
                <td v-for="y in x" v-bind:class="[ y == 1 ? 'green' : '', y == -1 ? 'red' : '' ]"><img v-if="y==1" src="img/queen.png" width="60" height="60" /></td>
            </tr>
        </table>
    </div>
    <canvas id="mycanvas" width="600" height="600"></canvas>
</body>
</html>