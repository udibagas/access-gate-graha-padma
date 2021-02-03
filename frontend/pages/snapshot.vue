<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">SNAPSHOT KAMERA</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button
						size="small"
						type="danger"
						icon="el-icon-delete"
						@click="deleteSnapshot"
						:disabled="checkedNodes.length == 0"
						>HAPUS SNAPSHOT</el-button
					>
				</el-form-item>
			</el-form>
		</div>

		<div class="d-flex">
			<div
				class="p-3 border-end"
				style="width: 350px; height: calc(100vh - 115px)"
			>
				<el-tree
					v-if="show"
					:props="props"
					:load="loadNode"
					lazy
					show-checkbox
					node-key="path"
					@node-click="({ isFile, url }) => (this.url = isFile ? url : '')"
					@check="(node, tree) => (checkedNodes = tree.checkedNodes)"
				>
				</el-tree>
			</div>

			<div
				class="p-3 flex-grow-1 d-flex align-items-center justify-content-center flex-column"
			>
				<img :src="url" alt="" style="width: 100%" />
			</div>
		</div>
	</el-card>
</template>

<script>
export default {
  data() {
    return {
      show: true,
      url: '',
      checkedNodes: [],
      props: {
        label: 'label',
        isLeaf: 'isFile',
      }
    }
  },

  methods: {
    loadNode(node, resolve) {
      const params = { directory: node.data === undefined ? 'snapshots' : node.data.path };
      this.$axios.$get('api/snapshot', { params }).then(r => resolve(r));
    },

    deleteSnapshot() {
      this.$confirm('Anda yakin?', 'Konfirmasi', { type: 'warning' }).then(() => {
        const params = { checkedNodes: this.checkedNodes };
        this.$axios.$delete('api/snapshot/delete', { params }).then(data => {
          this.$message({
            message: data.message,
            type: 'success'
          });
          this.reload();
        }).catch(e => {
          this.$message({
            message: e.response.data.message,
            type: 'error'
          });
        });
      }).catch(e => console.log(e));
    },

    reload() {
			this.show = false;
			this.$nextTick(() => {
        this.show = true;
        this.url = '';
			});
		},
  }
}
</script>

<style>
</style>
