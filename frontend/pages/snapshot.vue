<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">SNAPSHOT KAMERA</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button
						v-if="$auth.user.role == 1"
						size="small"
						type="primary"
						icon="el-icon-download"
						@click="dateRangeDialog = true"
						>DOWNLOAD SNAPSHOT</el-button
					>
				</el-form-item>
				<el-form-item class="mb-0">
					<el-button
						v-if="$auth.user.role == 1"
						size="small"
						type="danger"
						icon="el-icon-delete"
						@click="deleteSnapshot"
						:disabled="checkedNodes.length == 0"
						>HAPUS SNAPSHOT</el-button
					>
				</el-form-item>
				<el-form-item class="mb-0">
					<el-button
						icon="el-icon-refresh"
						type="primary"
						size="small"
						@click="refresh"
					></el-button>
				</el-form-item>
			</el-form>
		</div>

		<div class="d-flex">
			<div
				class="p-3 border-end"
				style="width: 400px; height: calc(100vh - 115px); overflow: auto"
			>
				<el-tree
					v-if="show"
					:props="props"
					:load="loadNode"
					ref="tree"
					lazy
					show-checkbox
					highlight-current
					node-key="path"
					@node-click="({ isFile, url }) => (this.url = isFile ? url : '')"
					@check="(node, tree) => (checkedNodes = tree.checkedNodes)"
				>
				</el-tree>
			</div>

			<div
				class="p-3 flex-grow-1 d-flex align-items-center justify-content-center flex-column"
			>
				<img :src="url" alt="" style="max-width: 800px" />
			</div>
		</div>

		<el-dialog
			:visible.sync="dateRangeDialog"
			title="PILIH TANGGAL"
			center
			width="500px"
		>
			<div class="text-center">
				<el-date-picker
					v-model="range"
					type="daterange"
					range-separator="-"
					start-placeholder="Dari tgl"
					end-placeholder="Sampai tgl"
					value-format="yyyy-MM-dd"
					format="dd-MMM-yyyy"
				>
				</el-date-picker>
			</div>

			<template #footer>
				<el-button icon="el-icon-close" @click="dateRangeDialog = false"
					>BATAL</el-button
				>
				<el-button icon="el-icon-download" type="primary" @click="download"
					>DOWNLOAD</el-button
				>
			</template>
		</el-dialog>
	</el-card>
</template>

<script>
export default {
	data() {
		return {
			url: '',
			show: true,
			checkedNodes: [],
			expandedNodes: [],
			dateRangeDialog: false,
			range: null,
			props: {
				label: 'label',
				isLeaf: 'isFile',
			},
		}
	},

	methods: {
		loadNode(node, resolve) {
			const params = {
				directory: node.data === undefined ? 'snapshots' : node.data.path,
			}
			this.$axios.$get('api/snapshot', { params }).then((r) => resolve(r))
		},

		deleteSnapshot() {
			this.$confirm('Anda yakin?', 'Konfirmasi', { type: 'warning' })
				.then(() => {
					this.$axios
						.$post('api/snapshot/delete', { checkedNodes: this.checkedNodes })
						.then((data) => {
							this.url = ''
							this.$message({ message: data.message, type: 'success' })
							this.checkedNodes.forEach((node) => this.$refs.tree.remove(node))
							this.checkedNodes = []
						})
						.catch((e) => {
							this.$message({
								message: e.response.data.message,
								type: 'error',
							})
						})
				})
				.catch((e) => console.log(e))
		},

		refresh() {
			this.show = false
			this.$nextTick(() => (this.show = true))
		},

		download() {
			if (!this.range) return

			window.open(
				`${this.$axios.defaults.baseURL}/api/snapshot/download?range[]=${this.range[0]}&range[]=${this.range[1]}`
			)

			setTimeout(() => {
				this.dateRangeDialog = false
				this.range = null
			}, 3000)
		},
	},
}
</script>
