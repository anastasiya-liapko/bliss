import React, { Component } from 'react';

class TableFilterInfo extends Component {
  constructor(props) {
    super(props);

    this.handleFilterClose = this.handleFilterClose.bind(this);
  }

  handleFilterClose() {
    this.props.onFilterClose();
  }

  render() {
    const {
      filterName,
      filterBy,
      filterStart,
      filterStartNames,
      filterEnd,
      filterEndNames,
    } = this.props;

    if (!filterBy.length) {
      return null;
    }

    let startValue = filterStart;
    let endValue = filterEnd;

    if (filterStartNames && filterStartNames[filterBy]) {
      startValue = filterStartNames[filterBy][filterStart];
    }

    if (filterEndNames && filterEndNames[filterBy]) {
      endValue = filterEndNames[filterBy][filterEnd];
    }

    return (
      <div className="mt-3 ml-1">
            <span className="badge bg-white text-secondary">
              {filterName}: {startValue + (endValue.length > 0 ? ` - ${endValue}` : '')}
              <button
                className="btn btn-link btn-sm text-secondary"
                aria-label="Закрыть"
                onClick={this.handleFilterClose}>
                <i className="fas fa-times" aria-hidden={true}/>
              </button>
            </span>
      </div>
    );
  }
}

export default TableFilterInfo;
