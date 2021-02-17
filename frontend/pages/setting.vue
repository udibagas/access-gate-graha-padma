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

		<el-form label-width="150px" label-position="left">
			<el-form-item label="Hapus Log Umur">
				<el-input v-model="form.hapus_log"></el-input>
			</el-form-item>
		</el-form>
	</el-card>
</template>

<script>
export default {
  middleware: 'admin',
  data() {
    return {
      form: {}
    }
  },
  methods: {
    save() {
      // TODO
    }
  }
}
</script>
