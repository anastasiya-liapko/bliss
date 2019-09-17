import React, { Component, Fragment } from 'react';
import TableFilterInfo from './TableFilterInfo.jsx';
import TableHeader from './TableHeader.jsx';
import TablePagination from './TablePagination.jsx';
import TableStatistics from './TableStatistics.jsx';
import FailedOrdersTableRow from './FailedOrdersTableRow.jsx';

class FailedOrdersTable extends Component {
  constructor(props) {
    super(props);

    this.handleFilterCloseClick = this.handleFilterCloseClick.bind(this);
    this.handleTableSortClick = this.handleTableSortClick.bind(this);
    this.handleTableFilterClick = this.handleTableFilterClick.bind(this);
    this.handleTableRowClick = this.handleTableRowClick.bind(this);
    this.handlePageClick = this.handlePageClick.bind(this);
  }

  handleFilterCloseClick() {
    this.props.onFilterCloseClick();
  }

  handleTableSortClick(orderBy) {
    this.props.onTableSortClick(orderBy);
  }

  handleTableFilterClick(orderBy, start, end) {
    this.props.onTableFilterClick(orderBy, start, end);
  }

  handleTableRowClick(orderId) {
    this.props.onTableRowClick(orderId);
  }

  handlePageClick(data) {
    this.props.onPageClick(data);
  }

  render() {
    const {
      error,
      isLoading,
      items,
      orderBy,
      isDescending,
      filterBy,
      filterName,
      filterStart,
      filterEnd,
      totalItems,
      totalSum,
      pageCount,
      offset,
    } = this.props;

    if (error) {
      return <div className="mt-4">Произошла ошибка. Перезагрузите страницу,
        возможно, вы не авторизированы в административной панели.</div>;
    }

    if (isLoading) {
      return (
        <div className="loading-container">
          <div>
            <i className="fas fa-sync fa-spin fa-3x" aria-hidden={true}/>
          </div>
        </div>
      );
    }

    return (
      <Fragment>

        <TableFilterInfo
          filterName={filterName}
          filterBy={filterBy}
          filterStart={filterStart}
          filterStartNames={{
            status: {
              declined_by_shop: 'Отклонён магазином',
              canceled_by_client_upon_receipt: 'Отменён покупателем при получении',
              declined_by_mfi: 'Отклонён ФО',
              canceled_by_client: 'Отклонён покупателем',
            },
          }}
          filterEnd={filterEnd}
          onFilterClose={this.handleFilterCloseClick}/>

        <div className="table-responsive-xl">
          <table className="table table_sticky-header table-striped table-bordered table-hover">
            <thead>
            <tr>
              <TableHeader
                name="№ заказа"
                slug="order_id_in_shop"
                active={orderBy === 'order_id_in_shop'}
                isDescending={isDescending}
                filterInputType="text"
                showSort={true}
                showFilter={true}
                onTableSortClick={this.handleTableSortClick}
                onTableFilterClick={this.handleTableFilterClick}/>
              <TableHeader
                name="Дата и время заказа"
                slug="time_of_creation"
                active={orderBy === 'time_of_creation'}
                isDescending={isDescending}
                filterInputType="date"
                showSort={true}
                showFilter={true}
                onTableSortClick={this.handleTableSortClick}
                onTableFilterClick={this.handleTableFilterClick}/>
              <th className="table__th">Состав заказа</th>
              <th className="table__th">Сумма заказа</th>
              <TableHeader
                name="Статус заказа"
                slug="status"
                active={orderBy === 'status'}
                isDescending={isDescending}
                filterInputType="select"
                values={[
                  {
                    slug: 'declined_by_shop',
                    name: 'Отклонён магазином',
                  },
                  {
                    slug: 'canceled_by_client_upon_receipt',
                    name: 'Отменён покупателем при получении',
                  },
                  {
                    slug: 'declined_by_mfi',
                    name: 'Отклонён ФО',
                  },
                  {
                    slug: 'canceled_by_client',
                    name: 'Отклонён покупателем',
                  },
                ]}
                showSort={true}
                showFilter={true}
                onTableSortClick={this.handleTableSortClick}
                onTableFilterClick={this.handleTableFilterClick}/>
            </tr>
            </thead>
            <tbody>
            {items.map(item => (
              <FailedOrdersTableRow
                key={item.id}
                item={item}
                onTableRowClick={this.handleTableRowClick}/>
            ))}
            </tbody>
          </table>
        </div>

        <TableStatistics
          total={totalItems}
          totalSum={totalSum}/>

        <TablePagination
          pageCount={pageCount}
          offset={offset}
          perPage={10}
          onPageClick={this.handlePageClick}/>

      </Fragment>
    );
  }
}

export default FailedOrdersTable;
