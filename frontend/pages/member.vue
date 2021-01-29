<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">KELOLA MEMBER</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button
						@click="openForm({})"
						type="primary"
						icon="el-icon-plus"
						size="small"
						title="Tambah Member"
					></el-button>
				</el-form-item>
				<el-form-item class="mb-0">
					<el-button
						@click="openForm({})"
						type="primary"
						icon="el-icon-download"
						size="small"
						title="Export Member"
						plain
					></el-button>
				</el-form-item>
				<el-form-item class="mb-0">
					<el-button
						@click="openForm({})"
						type="primary"
						icon="el-icon-upload2"
						size="small"
						title="Import Member"
						plain
					></el-button>
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
			height="calc(100vh - 197px)"
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

			<el-table-column prop="phone" label="Phone"></el-table-column>

			<el-table-column
				prop="card_number"
				label="Card Number"
				align="center"
				header-align="center"
			></el-table-column>

			<el-table-column
				prop="readable_expired_date"
				label="Expired Date"
				align="center"
				header-align="center"
			></el-table-column>

			<el-table-column
				label="Expired"
				width="100"
				align="center"
				header-align="center"
				column-key="expired"
				:filter-multiple="false"
				:filters="[
					{ text: 'YA', value: 'yes' },
					{ text: 'TIDAK', value: 'no' },
				]"
			>
				<template slot-scope="scope">
					<el-tag
						size="mini"
						:type="scope.row.is_expired ? 'danger' : 'success'"
						effect="dark"
						style="width: 100%"
					>
						{{ scope.row.is_expired ? 'YA' : 'TIDAK' }}
					</el-tag>
				</template>
			</el-table-column>

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

				<el-form-item label="Nomor HP" :class="{ 'is-error': errors.phone }">
					<el-input v-model="form.phone" placeholder="Nomor HP"></el-input>
					<div class="el-form-item__error" v-if="errors.phone">
						{{ errors.phone.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item
					label="Nomor Kartu"
					:class="{ 'is-error': errors.card_number }"
				>
					<el-input
						v-model="form.card_number"
						placeholder="Nomor Kartu"
					></el-input>
					<div class="el-form-item__error" v-if="errors.card_number">
						{{ errors.card_number.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item
					label="Masa Aktif"
					:class="{ 'is-error': errors.expired_date }"
				>
					<el-date-picker
						style="width: 100%"
						v-model="form.expired_date"
						type="date"
						format="dd-MMM-yyyy"
						value-format="yyyy-MM-dd"
						placeholder="Masa Aktif"
					></el-date-picker>

					<div class="el-form-item__error" v-if="errors.expired_date">
						{{ errors.expired_date.join(', ') }}
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
      url: '/api/member',
      paginated: true
    }
  }
}
</script>
