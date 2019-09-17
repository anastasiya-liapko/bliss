class Helper {
  static getLocation() {
    let location = `//${window.location.hostname}`;

    if (window.location.port) {
      location += `:${window.location.port}`;
    }

    return location;
  }
}

export default Helper;
