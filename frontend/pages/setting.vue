<template>
	<el-card>
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">SETTING</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button
						size="small"
						@click="save"
						type="primary"
						icon="el-icon-circle-check"
						>SIMPAN</el-button
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
