<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<strong class="text-muted mt-2">KELOLA USER</strong>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button
						@click="openForm({})"
						type="primary"
						icon="el-icon-plus"
					></el-button>
				</el-form-item>
				<el-form-item class="mb-0">
					<el-input
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
			height="calc(100vh - 205px)"
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
				:index="pagination.from"
			></el-table-column>
			<el-table-column prop="name" label="Name"></el-table-column>
			<el-table-column prop="email" label="Email"></el-table-column>

			<ActionColumn
				@refreshData="refreshData"
				@showForm="showForm"
				@deleteData="deleteData"
			/>
		</el-table>

		<div class="text-center my-3">
			<el-pagination
				background
				layout="prev, pager, next, sizes, total"
				:current-page="pagination.current_page"
				@current-change="currentChange"
				@size-change="sizeChange"
				:page-size="Number(pagination.per_page)"
				:page-sizes="pageSizes"
				:total="pagination.total"
			></el-pagination>
		</div>
	</el-card>
</template>

<script>
import crud from '../mixins/crud';

export default {
  mixins: [crud],
  data() {
    return {
      url: '/api/user'
    }
  }
}
</script>
