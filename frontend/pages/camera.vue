<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">KAMERA</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button
						@click="openForm({})"
						type="primary"
						icon="el-icon-plus"
						size="small"
						title="Tambah Kamera"
						>TAMBAH KAMERA</el-button
					>
				</el-form-item>
				<el-form-item class="mb-0">
					<el-input
						v-model="keyword"
						placeholder="Cari"
						prefix-icon="el-icon-search"
						clearable
						size="small"
						@change="
							() => {
								this.pagination.current_page = 1
								fetchData()
							}
						"
					></el-input>
				</el-form-item>
			</el-form>
		</div>

		<el-table
			height="calc(100vh - 171px)"
			stripe
			v-loading="loading"
			:data="tableData"
			@sort-change="sortChange"
			@filter-change="filterChange"
			:default-sort="{ prop: 'name', order: 'ascending' }"
		>
			<el-table-column
				type="index"
				label="#"
				:index="paginated ? pagination.from : 1"
			></el-table-column>
			<el-table-column prop="name" label="Name"></el-table-column>
			<el-table-column prop="url" label="URL"></el-table-column>
			<el-table-column prop="user" label="User"></el-table-column>
			<el-table-column prop="pass" label="Pass"></el-table-column>

			<ActionColumn
				@refreshData="refreshData"
				@openForm="openForm"
				@deleteData="deleteData"
			/>
		</el-table>

		<Pagination
			@current-change="currentChange"
			@size-change="sizeChange"
			:page-sizes="pageSizes"
			:pagination="pagination"
			:paginated="paginated"
			:total="tableData.length"
		/>

		<el-dialog
			title="MEMBER"
			:visible.sync="showForm"
			:before-close="closeForm"
			:close-on-click-modal="false"
		>
			<el-form label-width="180px" label-position="left">
				<el-form-item label="Nama" :class="{ 'is-error': errors.name }">
					<el-input v-model="form.name" placeholder="Nama"></el-input>
					<div class="el-form-item__error" v-if="errors.name">
						{{ errors.name.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="URL" :class="{ 'is-error': errors.url }">
					<el-input v-model="form.url" placeholder="URL"></el-input>
					<div class="el-form-item__error" v-if="errors.url">
						{{ errors.url.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="User" :class="{ 'is-error': errors.user }">
					<el-input v-model="form.user" placeholder="User"></el-input>
					<div class="el-form-item__error" v-if="errors.user">
						{{ errors.user.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="Pass" :class="{ 'is-error': errors.pass }">
					<el-input v-model="form.pass" placeholder="Pass"></el-input>
					<div class="el-form-item__error" v-if="errors.pass">
						{{ errors.pass.join(', ') }}
					</div>
				</el-form-item>
			</el-form>

			<div slot="footer">
				<el-button icon="el-icon-circle-close" @click="closeForm">
					BATAL
				</el-button>

				<el-button
					type="primary"
					icon="el-icon-success"
					@click="save"
					:loading="loading"
				>
					SIMPAN
				</el-button>
			</div>
		</el-dialog>
	</el-card>
</template>

<script>
import crud from '../mixins/crud';

export default {
  mixins: [crud],
  data() {
    return {
      url: '/api/camera'
    }
  }
}
</script>
