<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">KELOLA USER</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button
						size="small"
						@click="openForm({})"
						type="primary"
						icon="el-icon-plus"
						>TAMBAH USER</el-button
					>
				</el-form-item>
				<el-form-item class="mb-0">
					<el-input
						size="small"
						v-model="keyword"
						placeholder="Cari"
						prefix-icon="el-icon-search"
						clearable
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
			<el-table-column prop="email" label="Email"></el-table-column>

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

		<!-- FORM -->

		<el-dialog
			title="USER"
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

				<el-form-item label="Email" :class="{ 'is-error': errors.email }">
					<el-input v-model="form.email" placeholder="Email"></el-input>
					<div class="el-form-item__error" v-if="errors.email">
						{{ errors.email.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="Password" :class="{ 'is-error': errors.password }">
					<el-input
						type="password"
						v-model="form.password"
						placeholder="Password"
					></el-input>

					<div class="el-form-item__error" v-if="errors.password">
						{{ errors.password.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item
					label="Konfirmasi Password"
					:class="{ 'is-error': errors.password_confirmation }"
				>
					<el-input
						type="password"
						v-model="form.password_confirmation"
						placeholder="Konfirmasi Password"
					></el-input>

					<div class="el-form-item__error" v-if="errors.password_confirmation">
						{{ errors.password_confirmation.join(', ') }}
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
      url: '/api/user',
      paginated: false
    }
  }
}
</script>
