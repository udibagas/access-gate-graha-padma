<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">SNAPSHOT KAMERA</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button size="small" type="danger" icon="el-icon-delete"
						>HAPUS SNAPSHOT</el-button
					>
				</el-form-item>
			</el-form>
		</div>
	</el-card>
</template>

<script>
export default {

}
</script>

<style>
</style>
