<template>
  <section id="login">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h3>LOGIN</h3>
                <input type="text" id="username" placeholder="Input Username" autofocus>
                <input type="password" id="password" placeholder="Input Password">
                <button @click="login" class="btn btn-dark">LOGIN</button>
            </div>
        </div>
    </div>
</section>
</template>

<script setup>
function login () {
    .fetch('./controllers/checkUser.php')
        .then(response=>response.json)
        .then((data) => {
            
        })

}
</script>

<style>

</style>