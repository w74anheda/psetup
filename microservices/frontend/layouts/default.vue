<template>
    <div>
        THIS IS DEFAULT LAYOUT
        <slot />
    </div>
</template>

<script setup>

fetch(
    "http://127.0.0.1:8040/api/v1/auth/login/phonenumber/request",
    {
        method: 'POST',
        headers: {
            'Accept': 'Application/Json',
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.114 Safari/537.36 RuxitSynthetic/1.0 v4954752706012875637 t7725840419877098505 ath93eb305d altpriv cvcv=2 cexpw=1 smf=0',
        },
    }
).then(function (response) {
    return response.json();
}).then(function (response) {
    console.log(response)
})
</script>