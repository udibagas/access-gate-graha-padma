<template>
	<el-container>
		<el-main
			class="d-flex align-items-center justify-content-center flex-column bg"
			style="height: 100vh"
		>
			<Nuxt />
		</el-main>
	</el-container>
</template>

<script>
export default {
  // TODO: redirect if already login
}
</script>

<style lang="css" scoped>
.bg {
	background-image: url('/bg.jpg');
	background-repeat: no-repeat;
	background-size: cover;
}
</style>
