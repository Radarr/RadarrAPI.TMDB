<template>
    <div style="padding-left: 5%; padding-right: 5%">
        <table width="100%">
            <tr v-for="movie in movies" class="hvr-grow-shadow">
                <div class="hvr-grow">
                    <div class="background-image" :style="{'background-image': 'url(\'https://image.tmdb.org/t/p/w1280' + movie.backdrop_path + '\')'}" ></div>
                    <div class="background-color"></div>
                    <div style="padding: 10px">
                        <td width="10%">
                            {{movie.id}}
                        </td>
                        <td width="80%">
                            {{movie.original_title}}
                        </td>
                        <td>
                            <rating :ratings="movie.ratings"></rating>
                        </td>
                    </div>

                </div>
            </tr>
        </table>
    </div>

</template>

<script>
    import Rating from "./rating/Rating";
    export default {
        name: "ListView",
        components: {
            Rating
        },
        props: ["movies"],
        methods: {
            loadMovie(movie) {
                this.$router.push({ name: 'moviePage', params: { movie: movie } });
            },
            rowStyle(row) {
                console.log(row);
                return { backgroundImage : 'url("https://i0.wp.com/image.tmdb.org/t/p/w1280' + row.row.backdrop_path + '?w=400&filter=blurgaussian&smooth=1")'};
            }
        }
    }
</script>

<style lang="scss">
    tr {
        color:white;
        border-radius: 5px;
    }

    .background-image {
        width: 100%;
        height: 100%;
        position: absolute;
        z-index: -1;
        overflow: hidden;
        background-position: center;
        background-size: cover;
        background-color: rgba(0, 0, 0, 0.9);
        transition-duration: 0.3s;
        transition-property: z-index;
    }
    .background-color {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
        background-color: rgba(40, 40, 40, 0.5);
    }

    .background-image:hover {
        z-index: 1;
    }

    .hvr-grow-shadow {
        display: block;
        vertical-align: middle;
        /*-webkit-transform: perspective(1px);
        transform: perspective(1px);*/
        box-shadow: 0 0 1px rgba(0, 0, 0, 0);
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        z-index: 0;
        -webkit-transition-property: box-shadow, transform, z-index;
        transition-property: box-shadow, transform, z-index;
    }
    .hvr-grow-shadow:hover, .hvr-grow-shadow:focus, .hvr-grow-shadow:active {
        box-shadow: 0 20px 20px -20px rgba(0, 0, 0, 0.5);
        -webkit-transform: scale(1.05);
        transform: scale(1.05);
        z-index: 2;
    }

    .hvr-grow {
        display: block;
        position: relative;
        vertical-align: middle;
        transform: translateZ(0);
        height: 100px;
        width: 100%;
        backface-visibility: hidden;
        -moz-osx-font-smoothing: grayscale;
        transition: transform 0.3s, height 0.3s;
    }

    .hvr-grow:hover,
    .hvr-grow:focus,
    .hvr-grow:active {
        height: 500px;
    }
</style>