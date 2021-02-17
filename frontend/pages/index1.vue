<template>
	<div class="row bg-white p-3">
		<div class="col" v-for="gate in gates" :key="gate.id">
			<div class="card">
				<div class="card-header">
					<h2 class="text-center">{{ gate.name }}</h2>
				</div>

				<div class="card-body">
					<el-image>
						<div slot="error" class="image-slot">TIDAK ADA TAMU</div>
					</el-image>
				</div>

				<div class="text-footer bg-secondary p-3 text-white">
					<span class="label">Nama</span> : - <br />
					<span class="label">Nomor Kartu</span> : - <br />
					<span class="label">Plat Nomor</span> : - <br />
					<span class="label">Waktu</span> : -
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
  async asyncData({$axios}) {
    const gates = await $axios.$get('/api/accessGate', { params: { paginated: false } });
    return { gates };
  }
}
</script>

<style lang="css" scoped>
span.label {
	display: inline-block;
	width: 100px;
	font-weight: bold;
}
</style>
